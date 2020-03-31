// Infinite scroll script. A modification of following pen https://codepen.io/castroalves/pen/YdGyKY 
//
// Creds to:
// Author: Cadu de Castro Alves
// Twitter: https://twitter.com/castroalves
// GitHub: https://github.com/castroalves
const WPInfiniteScroll = (() => {
    // Basic Configuration
    const config = {
      api: linkinbio_scroll.url,
      startPage: 1, // 0 for the first page, 1 for the second and so on...
      postsPerPage: 6 // Number of posts to load per page
    };
    
    // Private Properties
    let postsLoaded = false;
    let postsContent = document.querySelector('.libio-container');
    let btnLoadMore = document.querySelector('.btn-load-more');
    
    // Private Methods
    const loadContent = function() {
      
        // Starts with page = 1
        // Increase every time content is loaded
        ++config.startPage;
      
        // Basic query parameters to filter the API
        // Visit https://developer.wordpress.org/rest-api/reference/posts/#arguments
        // For information about other parameters
        const params = {
          _embed: true, // Required to fetch images, author, etc
          page: config.startPage, // Current page of the collection
          per_page: config.postsPerPage, // Maximum number of posts to be returned by the API
        }
      
        // console.log('_embed', params._embed);
        // console.log('per_page', params.per_page);
        // console.log('page', params.page);
      
        // Builds the API URL with params _embed, per_page, and page
        const getApiUrl = (url) => {
          let apiUrl = new URL(url);
          apiUrl.search = new URLSearchParams(params).toString();
          return apiUrl;
        };
      
        // Make a request to the REST API
        const loadPosts = async () => {
          const url = getApiUrl(config.api);
          const request = await fetch(url);
          const links = parseLinkHeader( request.headers.get('Link') );
  
          if( !("next" in links) ){
              btnLoadMore.parentNode.removeChild(btnLoadMore);
          }
          const posts = await request.json();
          
          // Builds the HTML to show the posts
          const postsHtml = renderPostHtml(posts);
          
          // Adds the HTML into the posts div
          postsContent.innerHTML += postsHtml;
          
          // Required for the infinite scroll
          postsLoaded = true;
        };
      
        // Builds the HTML to show all posts
        const renderPostHtml = (posts) => {
          let postHtml = '';
          for(let post of posts) {
            postHtml += postTemplate(post);
          };
          return postHtml;
        };
      
        // HTML template for a post
        const postTemplate = (post) => { 
          let imgsrc = ( "image_link" in post._embedded['wp:featuredmedia'][0].media_details.sizes ) ? post._embedded['wp:featuredmedia'][0].media_details.sizes.image_link.source_url : post._embedded['wp:featuredmedia'][0].source_url;
          return `
              <div id="post-${post.id}" class="libio-photo-wrapper">
                <a class="libio-photo" href="${post.link}" target="_blank" >
                <img width="250" height="250" src="${imgsrc}" class="attachment-250x250 size-250x250 wp-post-image" sizes="(max-width: 250px) 100vw, 250px" />
                </a> 
              </div>
               `;
        };
  
        const parseLinkHeader = (header) => {
          var linkexp = /<[^>]*>\s*(\s*;\s*[^\(\)<>@,;:"\/\[\]\?={} \t]+=(([^\(\)<>@,;:"\/\[\]\?={} \t]+)|("[^"]*")))*(,|$)/g;
          var paramexp = /[^\(\)<>@,;:"\/\[\]\?={} \t]+=(([^\(\)<>@,;:"\/\[\]\?={} \t]+)|("[^"]*"))/g;
  
          var matches = header.match(linkexp);
          var rels = {};
          for (var i = 0; i < matches.length; i++) {
              var split = matches[i].split('>');
              var href = split[0].substring(1);
              var ps = split[1];
              var s = ps.match(paramexp);
              for (var j = 0; j < s.length; j++) {
                  var p = s[j];
                  var paramsplit = p.split('=');
                  var name = paramsplit[0];
                  var rel = paramsplit[1].replace(/["']/g, '');
                  rels[rel] = href;
              }
          }
          return rels;
        }
      
        loadPosts();
    };
    
    // Where the magic happens
    // Checks if IntersectionObserver is supported
    if ('IntersectionObserver' in window) {
      
      const loadMoreCallback = (entries, observer) => {
        entries.forEach((btn) => {
          if (btn.isIntersecting && postsLoaded === true) {
            postsLoaded = false;
            loadContent();
          }
        });
      };
      
      // Intersection Observer options
      const options = {
        threshold: 1.0 // Execute when button is 100% visible
      };
      
      let loadMoreObserver = new IntersectionObserver(loadMoreCallback, options);
      loadMoreObserver.observe(btnLoadMore);
    }
    
    // Public Properties and Methods
    return {
      init: loadContent
    };
    
  })();
  
  // Initialize Infinite Scroll
  WPInfiniteScroll.init();