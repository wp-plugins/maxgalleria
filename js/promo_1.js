jQuery(document).ready(function() {
  jQuery('.tablenav.bottom').after(
    '<div class="mg-promo"><p class="mg-promo-title">Try these terrific MaxGalleria Addons<br>Every Addon for $49 or any single Addon for $29 for 1 site</p>\n\
     <p><a href="http://maxgalleria.com/shop/maxgalleria-albums/?utm_source=mgrepo&utm_medium=promo&utm_campaign=mgrepo">Albums</a><br />\n\Organize your galleries into albums</p>\n\
     <p><a href="http://maxgalleria.com/shop/maxgalleria-facebook/?utm_source=mgrepo&utm_medium=promo&utm_campaign=mgrepo">Facebook</a><br />Add Facebook photos to galleries</p>\n\
     <p><a href="http://maxgalleria.com/shop/maxgalleria-image-carousel/?utm_source=mgrepo&utm_medium=promo&utm_campaign=mgrepo">Image Carousel</a><br />Turn your galleries into carousels</p>\n\
     <p><a href="http://maxgalleria.com/shop/maxgalleria-image-showcase/?utm_source=mgrepo&utm_medium=promo&utm_campaign=mgrepo">Image Showcase</a><br />Showcase image with thumbnails</p>\n\
     <p><a href="http://maxgalleria.com/shop/maxgalleria-image-slider/?utm_source=mgrepo&utm_medium=promo&utm_campaign=mgrepo">Image Slider</a><br />Turn your galleries into sliders</p>\n\
     <p><a href="https://maxgalleria.com/shop/maxgalleria-slick-for-wordpress/?utm_source=mgrepo&utm_medium=promo&utm_campaign=mgrepo">Slick For WordPress</a><br />The Last Carousel You’ll ever need!</p><p><a href="http://maxgalleria.com/shop/maxgalleria-instagram/?utm_source=mgrepo&utm_medium=promo&utm_campaign=mgrepo">Instagram</a><br />Add Instagram images to galleries</p><p><a href="http://maxgalleria.com/shop/maxgalleria-video-showcase/?utm_source=mgrepo&utm_medium=promo&utm_campaign=mgrepo">Video Showcase</a><br />Showcase video with thumbnails</p><p><a href="http://maxgalleria.com/shop/maxgalleria-vimeo/?utm_source=mgrepo&utm_medium=promo&utm_campaign=mgrepo">Vimeo</a><br />Use Vimeo videos in your galleries</p></div>'
  );
  jQuery('.wrap h2').append(
    '<div class="mg-logo">Brought to you by<a href="http://maxfoundry.com" target="_blank"><img src="' +
    mg_promo.pluginurl +
    '/images/max-foundry.png" alt="Max Foundry" /></a>makers of <a href="http://maxbuttons.com/?ref=mbpro" target="_blank">MaxButtons</a> and <a href="http://maxinbound.com/?ref=mbpro" target="_blank">MaxInbound</a></div>'
  );
});