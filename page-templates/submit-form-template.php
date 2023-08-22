<?php
/*
* Template Name: Submission Success Page
*/
get_header();


if( have_rows('page_builder') ):
    // loop through the rows of data
   while ( have_rows('page_builder') ) : the_row();
     ?>
     <div class="container">
       <div class="submission-success-wrapper">
         <div class="submission-content">
           <?php
              if( get_row_layout() == 'hero_text' ):
                $content = get_sub_field('hero_text');
                echo $content;
              endif;
            ?>
         </div>
         <div class="on-this-page sidebar">
             <div class="inner">
                 <p>In this section</p>
                 <ul>
                   <li>
                     <a href="/quiz/" id="1" class="circle dark-red">
                       <span class="material-icons">arrow_forward</span>
                       <span class="text">Dashboard</span>
                     </a>
                   </li>
                 </ul>
             </div>
         </div>
       </div>
    </div>
    <?php
    endwhile;

    ?>
    <style media="screen">
    #printTable {
     border-radius: 16px;
     background: #dfdfdf;
     overflow: hidden;
     border: 1px solid #dfdfdf;
    }
    #printTable .heading {
     font-size: 14px;
     font-weight: bold;
     text-shadow: 0 1px 0 #fff;
     text-align: left;
     padding: 12px;
    }
    #printTable .label {
     font-weight: 700;
     background-color: #eaf2fa;
     border-bottom: 1px solid #fff;
     line-height: 150%;
     padding: 7px 7px;
    }
    #printTable th{
      padding: 10px;
    }
    #printTable .label td {
     width: 100% !important;
    }
    #printTable .value {
     border-bottom: 1px solid #dfdfdf;
     padding: 7px 7px 7px 40px;
     line-height: 150%;
     background: #fff;
    }
    .entry-view-section-break{
      font-size: 14px;
      font-weight: 700;
      background-color: #eee;
      border-bottom: 1px solid #dfdfdf;
      padding: 7px 7px;
    }
    .wrapperTablePrint{
      position: absolute;
      z-index: -999;
      top: 0;
    }
    </style>
    <div class="wrapperTablePrint">
      <img id="logoPrint" style="margin: 0 auto;display:block" src="<?php echo get_template_directory_uri().'/assets/imgs/logo.svg'; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
      <table class='ss-print widefat fixed entry-detail-view' id='printTable'>
        <thead class='heading'>
          <th id="details">Quiz</th>
          <th style="font-size:10px;"></th>
        </thead>
      </table>
    </div>
    <?php

else :
    // no layouts found
endif;
?>

<?php
get_footer();
