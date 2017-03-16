<?php
if (! function_exists('my_add_excerpts_to_pages')) {
    add_action( 'init', 'my_add_excerpts_to_pages' );
    function my_add_excerpts_to_pages() {
         add_post_type_support( 'page', 'excerpt' );
    }
}

if (! function_exists('pelaajaesittely_by_page_id')) {

    function getFirstImgSrc($content = '') {
        $matches = [];
        $matchCount = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
        if (0 === $matchCount) {
            return null;
        }
        return $matches[1][0];
    }

    function pelaajaesittely_by_page_id($attrs) {

        if (! array_key_exists('id', $attrs)) {
            return '';
        }

        $pageId = $attrs['id'];
        $page = get_post($pageId);
        if (! $page) {
            return '';
        }

        $excerpt = $page->post_excerpt;
        $imgSrc = getFirstImgSrc($page->post_content);

        $ret = '<a href="' . $page->post_name . '" class="playerDisplay" style="%s">';
        $ret = sprintf($ret, (empty($imgSrc)
                ?  ''
                : "background-image: url('" . strip_tags($imgSrc) . "')"));
        $ret .= '<span>' . strip_tags($excerpt, '<strong><b><br>') . '</span></a>';

        return $ret;
    }

    function pelaajaesittely_css() {
        ?>
        <style type="text/css">
.playerDisplay {
  height: 200px;
  width: 200px;
  background-size: cover;
  background-position: center;
  position: relative;
  float: left;
  margin: 5px;
}
.playerDisplay:hover {
  transform: scale(1.05);
  transition: all .5s;
}

.playerDisplay span {
  background-color:rgba(255, 255, 255, 0.9);
  color: #5d5d5d;
  width: 90%;
  margin-left: 5%;
  padding: 5%;
  text-align: center;
  display: block;
  bottom: 5%;
  position: absolute;
  font-family: "Open Sans", Helvetica, Arial, sans-serif;
  font-size: 13px;
}
        </style>
        <?php
    }

    add_action('wp_head', 'pelaajaesittely_css' );
    add_shortcode('pelaajaesittely', 'pelaajaesittely_by_page_id');
}
