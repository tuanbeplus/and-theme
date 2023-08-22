(function( $ ){

  $(document).ready( function() {

    $('#continue-quiz-btn').click( function(e) {
      e.preventDefault();

      var quiz = $('.quizDetails').children('.quiz').length;
      var lastQuiz = $('.quizDetails .quiz').last().data('quiz');
      var opacity = $('.quizDetails .quiz').last().css('opacity');

      if ( lastQuiz && opacity == 1 ) {

        var $arr_question = [];
        var $arr_answers = [];
        var $arr_note = [];

        $('.stepsWrap .step-item-container').each( function(i) {

          var title = $(this).find('.count .title').text();
          $arr_question[i] = title;
          localStorage.setItem('arr_question', JSON.stringify($arr_question));
        });

        $('.quizDetails .quiz').each( function() {
          var arr_min = [];
          $(this).find('.checkBox input:checked').each( function() {
            arr_min.push($(this).data('title'));
          });
          $arr_answers.push(arr_min);

          var arr_note = [];
          $(this).find('.textAreaWrap textarea').each( function() {
            if ( $(this).val() != '') {
              arr_note.push($(this).val());
            }
          });
          $arr_note.push(arr_note);

        });
        localStorage.setItem('arr_answers', JSON.stringify($arr_answers));
        localStorage.setItem('arr_note', JSON.stringify($arr_note));

      }

    });

    function printData(){
       let home_url = window.location.origin;

       var divToPrint=document.getElementById("printTable");
       var logoPrint = document.getElementById("logoPrint");
       newWin= window.open("");
       newWin.document.write(logoPrint.outerHTML);
       newWin.document.write(divToPrint.outerHTML);
       newWin.print();
       newWin.close();
    }
    $('a#printEntry').on('click',function(e){
      e.preventDefault();
      printData();
    });

  });

  $(window).load( function() {
    if ( !$('body').hasClass('page-template-submit-form-template') ) {
      return;
    }
    var question = JSON.parse(localStorage.getItem("arr_question"));
    for (var i = 0; i < question.length; i++) {
      $('#printTable').append(
        "<tr id='head"+i+"' class='label entry-view-field-name'><td>"+ question[i] +"</td></tr><tr colspan='2' class='entry-view-section-break'></tr>"
      );
    }

    var answers = JSON.parse(localStorage.getItem("arr_answers"));
    for (var i = 0; i < answers.length; i++) {
      $('#printTable').find('#head' + i).after("<tr id='headSelect"+i+"'><td class='label entry-view-field-name'>Selected answers: "+ answers[i] +"</td><tr>");
    }

    var note = JSON.parse(localStorage.getItem("arr_note"));
    for (var i = 0; i < note.length; i++) {
      if ( note[i].length != 0 ) {
        $('#printTable').find('#headSelect' + i).after("<tr id='headDes"+i+"'><td class='label entry-view-field-name'>Description: "+ note[i] +"</td><tr>");
      }
    }

    if ( question && answers ) {
      $('.submission-success-wrapper .submission-content').find('span.status').html('100%');
    }else{
      $('.submission-success-wrapper .submission-content').find('span.status').html('Pending');
    }

    localStorage.removeItem('arr_question');
    localStorage.removeItem('arr_answers');
    localStorage.removeItem('arr_note');
  });

})( jQuery );
