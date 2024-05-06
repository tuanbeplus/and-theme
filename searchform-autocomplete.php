<?php
/* Form Search */

$suggestions_option = get_field('suggestions_option','options');
$placeholder_search = get_field('placeholder_search','options');
$text_button_search = get_field('text_button_search','options');

$text_button_search = $text_button_search ? $text_button_search : 'Search';
$placeholder_search = $placeholder_search ? $placeholder_search : 'Enter a search term';

?>
<div class="template-form-search">
  <div class="container">
    <button id="btn-close-search-popup" role="button" aria-label="Close search popup">
      <span class="xmark-icon"><i class="fa-solid fa-xmark"></i></span>
      <span>Close</span>
    </button>
    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">

        <label for="autocomplete_search">
          <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <title id="searchTitle">Search</title>
            <path d="M10.917 9.667h-.659l-.233-.225a5.393 5.393 0 001.308-3.525 5.416 5.416 0 10-5.416 5.416 5.393 5.393 0 003.525-1.308l.225.233v.659l4.166 4.158 1.242-1.242-4.158-4.166zm-5 0a3.745 3.745 0 01-3.75-3.75 3.745 3.745 0 013.75-3.75 3.745 3.745 0 013.75 3.75 3.745 3.745 0 01-3.75 3.75z" fill="#3D3D3D"/>
          </svg>
          <input id="autocomplete_search"
            class="search-field form-control"
            placeholder="<?php echo $placeholder_search; ?>"
            aria-label="Search through the site content"
            value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
        </label>

        <input type="submit"
          class="search-submit btn btn-default"
          aria-label="Search"
          value="<?php echo $text_button_search; ?>">

        <?php if($suggestions_option):
          $suggestions_option = explode(',',$suggestions_option);
          ?>
          <div class="suggestions-search">
            <span>Suggestions: </span>
            <?php foreach ($suggestions_option as $key => $suggestion): ?>
              <a class="btn-suggestions <?php if (($key+1) == count($suggestions_option)) echo 'last-btn'; ?>" href="javascript:;" 
                data-search="<?php echo trim($suggestion); ?>"><?php echo $suggestion; ?>
              </a>
              <?php echo (($key+1) < count($suggestions_option)) ? ', ' : ''; ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
    </form>
  </div>
</div>
