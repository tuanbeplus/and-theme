<?php
/*
* Template Name: Quiz HTML Template
*/

get_header();
?>

<div class="wrapper-quiz">
  <div class="container">
    <div class="wrap-quiz">

      <div class="_top">
        <h1 class="_title">Quiz</h1>

        <div class="__right">
          <a href="#" class="save_progress">Save Progress</a>
          <span>Your progress has been saved</span>
        </div>
      </div>

      <div class="gf_browser_chrome gform_wrapper">
        <form class="" action="" method="POST">
          <div id="gf_page_steps" class="gf_page_steps">
             <div id="gf_step_1" class="gf_step gf_step_active gf_step_first">
               <span class="gf_step_number">1</span>
               <span class="gf_step_label"> - Quiz 1</span>
             </div>
             <div id="gf_step_2" class="gf_step gf_step_pending">
               <span class="gf_step_number">2</span>
               <span class="gf_step_label"> - Quiz 2</span>
             </div>
             <div id="gf_step_3" class="gf_step gf_step_pending">
               <span class="gf_step_number">3</span>
               <span class="gf_step_label"> - Quiz 3</span>
             </div>
             <div id="gf_step_4" class="gf_step gf_step_pending">
               <span class="gf_step_number">4</span>
               <span class="gf_step_label"> - Quiz 4</span>
             </div>
             <div id="gf_step_5" class="gf_step gf_step_pending">
               <span class="gf_step_number">5</span>
               <span class="gf_step_label"> - Quiz 5</span>
             </div>
             <div id="gf_step_6" class="gf_step gf_step_pending">
               <span class="gf_step_number">6</span>
               <span class="gf_step_label"> - Quiz 6</span>
             </div>
             <div id="gf_step_7" class="gf_step gf_step_pending">
               <span class="gf_step_number">7</span>
               <span class="gf_step_label"> - Quiz 7</span>
             </div>
             <div id="gf_step_8" class="gf_step gf_step_pending">
               <span class="gf_step_number">8</span>
               <span class="gf_step_label"> - Quiz 8</span>
             </div>
             <div id="gf_step_9" class="gf_step gf_step_pending">
               <span class="gf_step_number">9</span>
               <span class="gf_step_label"> - Quiz 9</span>
             </div>
             <div id="gf_step_10" class="gf_step gf_step_last gf_step_pending">
               <span class="gf_step_number">10</span>
               <span class="gf_step_label"> - Quiz 10</span>
             </div>
          </div>
          <div class="gform_body">
            <div id="gform_page_1" class="gform_page active">
              <div class="gform_page_fields">
                <ul id="gform_fields" class="gform_fields">
                  <li id="field_1" class="gfield">
                    <div class="__title-quiz">1 - Quiz 1</div>
                  </li>
                  <li id="field_2" class="gfield gfield_contains_required">
                    <label class="gfield_label">Question 1
                      <span class="gfield_required">*</span>
                    </label>
                    <div class="gfield_description">1.1 Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum?”
                    </div>
                    <div class="ginput_container ginput_container_radio">
                        <ul class="gfield_radio" id="input_1">
                            <li class="gchoice_1_0">
                              <input name="input_1" type="radio" value="Yes" id="choice_1_0">
                              <label for="choice_1_0" id="label_1_0">Yes</label>
                            </li>
                            <li class="gchoice_1_1">
                              <input name="input_1" type="radio" value="Not Yet" id="choice_1_1">
                              <label for="choice_1_1" id="label_1_1">Not Yet</label>
                            </li>
                        </ul>
                    </div>
                  </li>
                  <li id="field_3" class="gfield gfield_contains_required">
                    <label class="gfield_label">Question 2<span class="gfield_required">*</span></label>
                    <div class="gfield_description">1.2 Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                        Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                    </div>
                    <div class="ginput_container ginput_container_radio">
                        <ul class="gfield_radio" id="input_2">
                            <li class="gchoice_2_0">
                              <input name="input_2" type="radio" value="Yes" id="choice_2_0">
                              <label for="choice_2_0" id="label_2_0">Yes</label>
                            </li>
                            <li class="gchoice_2_1">
                              <input name="input_2" type="radio" value="Not Yet" id="choice_2_1">
                              <label for="choice_2_1" id="label_2_1">Not Yet</label>
                            </li>
                        </ul>
                    </div>
                  </li>
                  <li id="field_4" class="gfield gfield_contains_required">
                      <label class="gfield_label" for="input_4">Question 2.1<span class="gfield_required">*</span></label>
                      <div class="ginput_container ginput_container_textarea">
                        <textarea name="input_4" id="input_4" class="textarea medium" placeholder="Lorem ipsum provide supporting answer" aria-required="true" aria-invalid="false" rows="10" cols="50"></textarea>
                      </div>
                  </li>
                  <li id="field_5" class="gfield">
                    <label class="gfield_label" for="input_5">Question 2.2</label>
                    <div class="ginput_container ginput_container_fileupload">
                        <input type="hidden" name="MAX_FILE_SIZE" value="52428800"><input name="input_5" id="input_5" type="file" class="medium" aria-describedby="validation_message_5 live_validation_message_5 extensions_message_5" onchange="javascript:gformValidateFileSize( this, 52428800 );"><span id="extensions_message_5" class="screen-reader-text">Accepted file types: ppt, pdf, docx.</span>
                    </div>
                    <div class="gfield_description" id="gfield_description_5">Maximum file size: 50MB<br>
                        File types allowed: .ppt, .pdf, .docx
                    </div>
                  </li>
                  <li id="field_6" class="gfield">
                      <div class="__advice">
                          <p>Advice</p>
                          <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
                      </div>
                  </li>
                </ul>
              </div>
              <div class="gform_page_footer">
                  <input type="button" data-page="2" id="gform_next_button_1" class="gform_next_button button" value="Continue">
              </div>
            </div>
            <div id="gform_page_2" class="gform_page">
              <div class="gform_page_fields">
                <ul id="gform_fields_2" class="gform_fields">
                  <li id="field_34" class="gfield">
                    <div class="__title-quiz">2 - Quiz 2</div>
                  </li>
                  <li id="field_7" class="gfield gfield_contains_required">
                      <label class="gfield_label">Question 3<span class="gfield_required">*</span></label>
                      <div class="gfield_description" id="gfield_description_7">1.1 Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                          Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum?”
                      </div>
                      <div class="ginput_container ginput_container_radio">
                          <ul class="gfield_radio" id="input_7">
                              <li class="gchoice_7_0"><input name="input_7" type="radio" value="Yes" id="choice_7_0"><label for="choice_7_0" id="label_7_0">Yes</label></li>
                              <li class="gchoice_7_1"><input name="input_7" type="radio" value="Not Yet" id="choice_7_1"><label for="choice_7_1" id="label_7_1">Not Yet</label></li>
                          </ul>
                      </div>
                  </li>
                  <li id="field_8" class="gfield gfield_contains_required">
                      <label class="gfield_label">Question 4<span class="gfield_required">*</span></label>
                      <div class="gfield_description" id="gfield_description_8">1.2 Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                          Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                      </div>
                      <div class="ginput_container ginput_container_radio">
                          <ul class="gfield_radio" id="input_8">
                              <li class="gchoice_8_0"><input name="input_8" type="radio" value="Yes" id="choice_8_0"><label for="choice_8_0" id="label_8_0">Yes</label></li>
                              <li class="gchoice_8_1"><input name="input_8" type="radio" value="Not Yet" id="choice_8_1"><label for="choice_8_1" id="label_8_1">Not Yet</label></li>
                          </ul>
                      </div>
                  </li>
                  <li id="field" class="gfield gfield_contains_required">
                      <label class="gfield_label" for="input">Question 4.1<span class="gfield_required">*</span></label>
                      <div class="ginput_container ginput_container_textarea">
                        <textarea name="input" id="input" class="textarea medium" placeholder="Lorem ipsum provide supporting answer" aria-required="true" aria-invalid="false" rows="10" cols="50"></textarea>
                      </div>
                  </li>
                  <li id="field_44" class="gfield gfield_html">
                    <div class="__advice">
                      <p>Advice</p>
                      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="gform_page_footer">
                  <input type="button" id="gform_previous_button_1" data-page="1" class="gform_previous_button button" value="Go Back">
                  <input type="button" id="gform_next_button_2" data-page="3" class="gform_next_button button" value="Continue" onclick="" onkeypress="">
              </div>
            </div>
            <div id="gform_page_3" class="gform_page">
              <div class="gform_page_fields">
                <ul id="gform_fields_3" class="gform_fields">
                  <li id="field_9_35" class="gfield gfield_html">
                     <div class="__title-quiz">3 - Quiz 3</div>
                  </li>
                  <li id="field_9_16" class="gfield gfield_contains_required">
                     <label class="gfield_label">Question 5<span class="gfield_required">*</span></label>
                     <div class="gfield_description" id="gfield_description_9_16">5.1 Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                        Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                     </div>
                     <div class="ginput_container ginput_container_radio">
                        <ul class="gfield_radio" id="input_9_16">
                           <li class="gchoice_9_16_0"><input name="input_16" type="radio" value="Yes" id="choice_9_16_0">
                             <label for="choice_9_16_0" id="label_9_16_0">Yes</label>
                           </li>
                           <li class="gchoice_9_16_1">
                             <input name="input_16" type="radio" value="Not Yet" id="choice_9_16_1">
                             <label for="choice_9_16_1" id="label_9_16_1">Not Yet</label>
                           </li>
                        </ul>
                     </div>
                  </li>
                  <li id="field_9_17" class="gfield gfield_contains_required">
                     <label class="gfield_label" for="input_9_17">Question 5.2<span class="gfield_required">*</span></label>
                     <div class="ginput_container ginput_container_textarea">
                       <textarea name="input_17" id="input_9_17" class="textarea medium" placeholder="Lorem ipsum provide supporting answer" aria-required="true" aria-invalid="false" rows="10" cols="50"></textarea>
                     </div>
                  </li>
                  <li id="field_9_45" class="gfield gfield_html gfield_html_formatted gfield_no_follows_desc field_sublabel_below field_description_below gfield_visibility_visible">
                    <div class="__advice">
                      <p>Advice</p>
                      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="gform_page_footer">
                  <input type="button" id="gform_previous_button_2" data-page="2" class="gform_previous_button button" value="Go Back">
                  <input type="button" id="gform_next_button_3" data-page="4" class="gform_next_button button" value="Continue" onclick="" onkeypress="">
              </div>
            </div>
            <div id="gform_page_4" class="gform_page">
              <div class="gform_page_fields">
                <ul id="gform_fields_4" class="gform_fields">
                  <li id="field_9_36" class="gfield gfield_html gfield_html_formatted gfield_no_follows_desc field_sublabel_below field_description_below gfield_visibility_visible">
                     <div class="__title-quiz">4 - Quiz 4</div>
                  </li>
                  <li id="field_9_19" class="gfield gfield_contains_required">
                     <label class="gfield_label">Question 6<span class="gfield_required">*</span></label>
                     <div class="gfield_description" id="gfield_description_9_19">6.1 Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                        Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                     </div>
                     <div class="ginput_container ginput_container_radio">
                        <ul class="gfield_radio" id="input_9_19">
                           <li class="gchoice_9_19_0"><input name="input_19" type="radio" value="Yes" id="choice_9_19_0"><label for="choice_9_19_0" id="label_9_19_0">Yes</label></li>
                           <li class="gchoice_9_19_1"><input name="input_19" type="radio" value="Not Yet" id="choice_9_19_1"><label for="choice_9_19_1" id="label_9_19_1">Not Yet</label></li>
                        </ul>
                     </div>
                  </li>
                  <li id="field_9_20" class="gfield gfield_contains_required">
                     <label class="gfield_label">Question 6.2<span class="gfield_required">*</span></label>
                     <div class="gfield_description" id="gfield_description_9_20">6.2 Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                        Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                     </div>
                     <div class="ginput_container ginput_container_radio">
                        <ul class="gfield_radio" id="input_9_20">
                           <li class="gchoice_9_20_0"><input name="input_20" type="radio" value="Yes" id="choice_9_20_0"><label for="choice_9_20_0" id="label_9_20_0">Yes</label></li>
                           <li class="gchoice_9_20_1"><input name="input_20" type="radio" value="Not Yet" id="choice_9_20_1"><label for="choice_9_20_1" id="label_9_20_1">Not Yet</label></li>
                        </ul>
                     </div>
                  </li>
                  <li id="field_9_46" class="gfield gfield_html">
                     <div class="__advice">
                        <p>Advice</p>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
                     </div>
                  </li>
                </ul>
              </div>
              <div class="gform_page_footer">
                  <input type="button" id="gform_previous_button_3" data-page="3" class="gform_previous_button button" value="Go Back">
                  <input type="button" id="gform_next_button_4" data-page="5" class="gform_next_button button" value="Continue" onclick="" onkeypress="">
              </div>
            </div>
            <div id="gform_page_5" class="gform_page">
              <div class="gform_page_fields">
                <ul id="gform_fields_5" class="gform_fields">
                  <li id="field_9_37" class="gfield gfield_html">
                     <div class="__title-quiz">5 - Quiz 5</div>
                  </li>
                  <li id="field_9_22" class="gfield gfield_contains_required">
                     <label class="gfield_label">Question 7<span class="gfield_required">*</span></label>
                     <div class="gfield_description" id="gfield_description_9_22">7.1 Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                        Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                     </div>
                     <div class="ginput_container ginput_container_radio">
                        <ul class="gfield_radio" id="input_9_22">
                           <li class="gchoice_9_22_0"><input name="input_22" type="radio" value="Yes" id="choice_9_22_0"><label for="choice_9_22_0" id="label_9_22_0">Yes</label></li>
                           <li class="gchoice_9_22_1"><input name="input_22" type="radio" value="Not Yet" id="choice_9_22_1"><label for="choice_9_22_1" id="label_9_22_1">Not Yet</label></li>
                        </ul>
                     </div>
                  </li>
                  <li id="field_9_47" class="gfield gfield_html">
                     <div class="__advice">
                        <p>Advice</p>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
                     </div>
                  </li>
                </ul>
              </div>
              <div class="gform_page_footer">
                  <input type="button" id="gform_previous_button_4" data-page="4" class="gform_previous_button button" value="Go Back">
                  <input type="button" id="gform_next_button_5" data-page="6" class="gform_next_button button" value="Continue" onclick="" onkeypress="">
              </div>
            </div>
            <div id="gform_page_6" class="gform_page">
              <div class="gform_page_fields">
                <ul id="gform_fields_6" class="gform_fields">
                  <li id="field_9_38" class="gfield gfield_html">
                     <div class="__title-quiz">6 - Quiz 6</div>
                  </li>
                  <li id="field_9_24" class="gfield gfield_contains_required">
                     <label class="gfield_label">Question 8<span class="gfield_required">*</span></label>
                     <div class="gfield_description" id="gfield_description_9_24">8.1 Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                        Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                     </div>
                     <div class="ginput_container ginput_container_radio">
                        <ul class="gfield_radio" id="input_9_24">
                           <li class="gchoice_9_24_0"><input name="input_24" type="radio" value="Yes" id="choice_9_24_0"><label for="choice_9_24_0" id="label_9_24_0">Yes</label></li>
                           <li class="gchoice_9_24_1"><input name="input_24" type="radio" value="Not Yet" id="choice_9_24_1"><label for="choice_9_24_1" id="label_9_24_1">Not Yet</label></li>
                        </ul>
                     </div>
                  </li>
                  <li id="field_9_48" class="gfield gfield_html">
                     <div class="__advice">
                        <p>Advice</p>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
                     </div>
                  </li>
                </ul>
              </div>
              <div class="gform_page_footer">
                  <input type="button" id="gform_previous_button_5" data-page="5" class="gform_previous_button button" value="Go Back">
                  <input type="button" id="gform_next_button_6" data-page="7" class="gform_next_button button" value="Continue" onclick="" onkeypress="">
              </div>
            </div>
            <div id="gform_page_7" class="gform_page">
              <div class="gform_page_fields">
                <ul id="gform_fields_7" class="gform_fields">
                  <li id="field_9_39" class="gfield gfield_html">
                     <div class="__title-quiz">7 - Quiz 7</div>
                  </li>
                  <li id="field_9_26" class="gfield gfield_contains_required">
                     <label class="gfield_label">Question 9<span class="gfield_required">*</span></label>
                     <div class="gfield_description" id="gfield_description_9_26">9.1 Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                        Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                     </div>
                     <div class="ginput_container ginput_container_radio">
                        <ul class="gfield_radio" id="input_9_26">
                           <li class="gchoice_9_26_0"><input name="input_26" type="radio" value="Yes" id="choice_9_26_0"><label for="choice_9_26_0" id="label_9_26_0">Yes</label></li>
                           <li class="gchoice_9_26_1"><input name="input_26" type="radio" value="Not Yet" id="choice_9_26_1"><label for="choice_9_26_1" id="label_9_26_1">Not Yet</label></li>
                        </ul>
                     </div>
                  </li>
                  <li id="field_9_49" class="gfield gfield_html">
                     <div class="__advice">
                        <p>Advice</p>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
                     </div>
                  </li>
                </ul>
              </div>
              <div class="gform_page_footer">
                <input type="button" id="gform_previous_button_6" data-page="6" class="gform_previous_button button" value="Go Back">
                <input type="button" id="gform_next_button_7" data-page="8" class="gform_next_button button" value="Continue" onclick="" onkeypress="">
              </div>
            </div>
            <div id="gform_page_8" class="gform_page">
              <div class="gform_page_fields">
                <ul id="gform_fields_8" class="gform_fields">
                  <li id="field_9_40" class="gfield">
                    <div class="__title-quiz">8 - Quiz 8</div>
                  </li>
                  <li id="field_9_28" class="gfield gfield_contains_required">
                     <label class="gfield_label">Question 10<span class="gfield_required">*</span></label>
                     <div class="gfield_description" id="gfield_description_9_28">10.1 Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                        Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                     </div>
                     <div class="ginput_container ginput_container_radio">
                        <ul class="gfield_radio" id="input_9_28">
                           <li class="gchoice_9_28_0"><input name="input_28" type="radio" value="Yes" id="choice_9_28_0"><label for="choice_9_28_0" id="label_9_28_0">Yes</label></li>
                           <li class="gchoice_9_28_1"><input name="input_28" type="radio" value="Not Yet" id="choice_9_28_1"><label for="choice_9_28_1" id="label_9_28_1">Not Yet</label></li>
                        </ul>
                     </div>
                  </li>
                  <li id="field_9_50" class="gfield gfield_html">
                     <div class="__advice">
                        <p>Advice</p>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
                     </div>
                  </li>
                </ul>
              </div>
              <div class="gform_page_footer">
                <input type="button" id="gform_previous_button_7" data-page="7" class="gform_previous_button button" value="Go Back">
                <input type="button" id="gform_next_button_8" data-page="9" class="gform_next_button button" value="Continue" onclick="" onkeypress="">
              </div>
            </div>
            <div id="gform_page_9" class="gform_page">
              <div class="gform_page_fields">
                <ul id="gform_fields_9" class="gform_fields">
                  <li id="field_9_41" class="gfield gfield_html">
                     <div class="__title-quiz">9 - Quiz 9</div>
                  </li>
                  <li id="field_9_30" class="gfield gfield_contains_required">
                     <label class="gfield_label">Question 11<span class="gfield_required">*</span></label>
                     <div class="gfield_description" id="gfield_description_9_30">11.1 Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                        Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                     </div>
                     <div class="ginput_container ginput_container_radio">
                        <ul class="gfield_radio" id="input_9_30">
                           <li class="gchoice_9_30_0"><input name="input_30" type="radio" value="Yes" id="choice_9_30_0"><label for="choice_9_30_0" id="label_9_30_0">Yes</label></li>
                           <li class="gchoice_9_30_1"><input name="input_30" type="radio" value="Not Yet" id="choice_9_30_1"><label for="choice_9_30_1" id="label_9_30_1">Not Yet</label></li>
                        </ul>
                     </div>
                  </li>
                  <li id="field_9_51" class="gfield gfield_html">
                     <div class="__advice">
                        <p>Advice</p>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
                     </div>
                  </li>
                </ul>
              </div>
              <div class="gform_page_footer">
                <input type="button" id="gform_previous_button_8" data-page="8" class="gform_previous_button button" value="Go Back">
                <input type="button" id="gform_next_button_9" data-page="10" class="gform_next_button button" value="Continue" onclick="" onkeypress="">
              </div>
            </div>
            <div id="gform_page_10" class="gform_page">
              <div class="gform_page_fields">
                <ul id="gform_fields_10" class="gform_fields">
                  <li id="field_9_42" class="gfield gfield_html">
                     <div class="__title-quiz">10 - Quiz 10</div>
                  </li>
                  <li id="field_9_32" class="gfield gfield_contains_required">
                     <label class="gfield_label">Question 12<span class="gfield_required">*</span></label>
                     <div class="gfield_description" id="gfield_description_9_32">12.1 Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                        Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                     </div>
                     <div class="ginput_container ginput_container_radio">
                        <ul class="gfield_radio" id="input_9_32">
                           <li class="gchoice_9_32_0">
                             <input name="input_32" type="radio" value="Yes" id="choice_9_32_0">
                             <label for="choice_9_32_0" id="label_9_32_0">Yes</label>
                           </li>
                           <li class="gchoice_9_32_1">
                             <input name="input_32" type="radio" value="Not Yet" id="choice_9_32_1">
                             <label for="choice_9_32_1" id="label_9_32_1">Not Yet</label>
                           </li>
                        </ul>
                     </div>
                  </li>
                  <li id="field_9_52" class="gfield gfield_html">
                    <div class="__advice">
                       <p>Advice</p>
                       <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
                    </div>
                 </li>
                </ul>
              </div>
              <div class="gform_page_footer">
                <input type="button" id="gform_previous_button_9" data-page="9" class="gform_previous_button button" value="Go Back">
                <input type="submit" id="gform_submit_button" class="gform_button button" value="Submit">
              </div>
            </div>

          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<?php
get_footer();
