/*
 * Copyright (C) 2016 Gabriel Pereira <rickardch@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

define(['jquery', 'mathjax', 'jquerycolumnizer', 'jqueryprint'], function () {
    var prepareApplication = (function () {
        
        prepareApplicationListeners = function () {

            /*
             * Abre a janela de impressão do gabarito
             * 
             */
            $('#print-answer-key').click(function () {
                generateAnswerKey();
            
                var answersTablesTitle = $('#answers-tables-title-template > div').clone();
                answersTablesTitle.attr('id', 'answers-title');
                $('#answer-key-tables').prepend(answersTablesTitle);
                        
                $('#answer-key-tables').print({
                    globalStyles: true,
                    mediaPrint: true,
                    stylesheet: null,
                    noPrintSelector: null,
                    iframe: true,
                    append: null,
                    prepend: null,
                    manuallyCopyFormValues: true,
                    deferred: $.Deferred(),
                    timeout: 750,
                    title: null,
                    doctype: '<!doctype html>'
                });
            });

            /*
             * Abre a janela de impressão da prova
             * 
             */
            $('.print-exam').click(function () {
                console.time('print time');
                var contentInfoBlock = $(this).closest('.content-info-block');
                var contentId = +contentInfoBlock.data('content-id');
                
                showOnlyAppropriateButtons(contentInfoBlock, true);                
                setLoadingText(contentInfoBlock, 'Iniciando geração da prova');
                
                setLoadingText(contentInfoBlock, 'Ajustando imagens a largura das colunas');
                ajustImages(contentInfoBlock);
                                
                var pageNumber = 1;
                var instructionsPage = $();
                if ($(this).closest('.exam').find('.exam-instructions').is(":checked")) {
                    setLoadingText(contentInfoBlock, 'Criando página de instruções');
                    
                    instructionsPage = $("#exam-page-template > div").clone();
                    instructionsPage
                            .find('.exam-content')
                            .first()
                            .append($('#instructions-template-' + contentId + ' > div').clone());
                    instructionsPage.find('.page-number').first().text(pageNumber);
                    
                    ++pageNumber;
                }
                
                //  Prepara a div .print-page (columnizer) e imprimi no callback
                var printDiv = $('#print-page-' + contentId);
                
                setTimeout(function () {
                    // gera a div de impressão
                    generateExam(contentInfoBlock, printDiv, pageNumber, function() {
                        // typeset
                        MathJax.Hub.Queue(["Typeset", MathJax.Hub, printDiv.data('div-id')], function () {
                            var printCallback = function() {
                                printDiv.html('');
                                console.timeEnd('print time');

                                showOnlyAppropriateButtons(contentInfoBlock, false);
                            };
                            
                            //  Abre a janela de impressão da div .print-page usando jqueryprint
                            printDiv.print({
                                globalStyles: true,
                                mediaPrint: true,
                                stylesheet: null,
                                noPrintSelector: null,
                                iframe: true,
                                append: null,
                                prepend: instructionsPage,
                                manuallyCopyFormValues: true,
                                deferred: $.Deferred().done(printCallback),
                                timeout: 1000,
                                title: contentInfoBlock.find('.exam-name').first().text(),
                                doctype: '<!doctype html>'
                            });
                        }); 
                    });
                }, 500);   
                
                
                /*
                 * Utilizando as classes .hide-when-printing e .show-when-printing,
                 * esconde e exibe, respectivamente, esses elementos durante ou após
                 * o processo de impressão da prova.
                 * @param {object} contentInfoBlock - jQuery object do bloco de informações
                 *      de um conteúdo da prova
                 * @param {boolean} printing - flag que indica se a prova está 
                 *      sendo impressa (true), ou acabou de ser impressa (false)
                 */
                function showOnlyAppropriateButtons(contentInfoBlock, printing) {
                    if (printing) {
                        contentInfoBlock.find('.hide-when-printing').addClass('hide');
                        contentInfoBlock.find('.show-when-printing').removeClass('hide');
                    } else {
                        contentInfoBlock.find('.hide-when-printing').removeClass('hide');
                        contentInfoBlock.find('.show-when-printing').addClass('hide');
                    }
                }
            });
        };

        /*
         * Ajusta o tamanho das imagens do conteúdo para que sua largura seja, 
         * no máximo, igual a largura da(s) coluna(s) da prova
         * 
         * @param {object} contentInfoBlock - jQuery object do bloco de informações
         *      de um conteúdo da prova
         */
        ajustImages = function(contentInfoBlock) {
            if (contentInfoBlock.hasClass('adjustment-done')) {
                return;
            }
            
            contentInfoBlock.find('.content-questions img').each(function() {
                var imgWidth = $(this).width();
                var columnWidth = $(this).closest('.question-block').width();
                if (imgWidth > columnWidth) {
                    $(this).width(columnWidth);
                }
            });
            
            contentInfoBlock.addClass('adjustment-done');
        };
        
        /*
         * Altera a mensagem da barra de carregamento
         * 
         * @param {object} contentInfoBlock - DOM Object do bloco de informações
         *      do conteúdo da prova
         * @param {string} message - conteúdo da mensagem 
         */
        setLoadingText = function(contentInfoBlock, message) {
            contentInfoBlock.find('.loading-message').text(message);
        };
        
        /*
         * Altera a alerta da barra de carregamento 
         * 
         * @param {object} contentInfoBlock - DOM Object do bloco de informações
         *      do conteúdo da prova
         * @param {string} message - conteúdo da mensagem 
         */
        setAlertText = function(contentInfoBlock, message) {
            contentInfoBlock.find('.alert-message').text(message);
        };


        /*
         * Configura a prova (cabeçalho, colunas, rodapé) e gera a versão 
         * de impressão na div com id printDivId
         * 
         * @param {object} contentInfoBlock - DOM Object do bloco de informações
         *      do conteúdo da prova
         * @param {object} printDiv - DOM Object da div que será usada para impressão
         * @param {int} initialPageNumber - número da primeira página dessa prova
         * @param {function} callback
         */
        generateExam = function (contentInfoBlock, printDiv, initialPageNumber, callback) {
            var pageNumber = initialPageNumber;
            var contentQuestions = contentInfoBlock.find('.content-questions').first();
            var totalQuestions = contentQuestions.find('.question-block').length;
            
            // Cria um div temporária que possuirá todo o conteúdo e servirá de 
            // fonte para o jquerycolumnizer gerar a div para impressão            
            printDiv.closest('.exam').append('<div id="exam-temp"></div>');
            var examTemp = $('#exam-temp');
            examTemp.html(contentQuestions.html());
            
            setLoadingText(contentInfoBlock, 'Removendo elementos desnecessários');
            updateProgressBar(totalQuestions, totalQuestions, printDiv, '');
            examTemp.find('.do-not-print').each(function () {
                $(this).remove();
            });
            
            setLoadingText(contentInfoBlock, 'Criando paginação - Redação');
            updateProgressBar(totalQuestions, totalQuestions, printDiv);
            
            var examContentHeight = $("#exam-page-template")
                    .find('.exam-content')
                    .first()
                    .height();
            // por enquanto, assume-se que existe apenas uma disciplina 
            // de coluna única, que ficará no topo da prova e terá 
            // como título o nome do base subject
            examTemp.find('.single-column-block').each(function() {
                var singleColumnSubjectPage = $("#exam-page-template > div").clone();
                
                if ($(this).height() < examContentHeight) {
                    var singleColumnSubjectBlock = $(this).clone();
                    var baseSubjectName =  $(this)
                            .closest('.base-subject-block').find('h3').first().text();
                    
                    singleColumnSubjectBlock.find('.title').first().text(baseSubjectName);
                    singleColumnSubjectBlock.find('.question-block')
                            .css('text-align', 'justify');

                    singleColumnSubjectPage.find('.exam-content')
                            .append(singleColumnSubjectBlock);
                    singleColumnSubjectPage.find('.page-number').text(pageNumber);
                    ++pageNumber;
                } else {
                    // repartir conteúdo
                    ++pageNumber;
                }
                
                printDiv.append(singleColumnSubjectPage);
                $(this).closest('.base-subject-block').remove();
            });
            
            setLoadingText(contentInfoBlock, 'Criando paginação - Questões de múltipla escolha');
            setAlertText(contentInfoBlock, 'Isto pode demorar alguns minutos');
            updateProgressBar(totalQuestions, totalQuestions, printDiv);
            
            buildExamLayout(contentInfoBlock, printDiv, totalQuestions, callback);
            
            
            /*
             * A div para impressão assume o template da prova (cabeçalho, 
             * corpo com duas colunas e rodapé) e é preenchida com o conteúdo 
             * da div temporária, que fica vazia.
             * 
             * @param {object} contentInfoBlock
             * @param {object} printDiv
             * @param {int} totalQuestions
             * @param {function} callback
             */
            function buildExamLayout(contentInfoBlock, printDiv, totalQuestions, callback) {
                var questionsLeft = $('#exam-temp').find('.question-block');
                
                if (questionsLeft.length > 0 /*há, pelo menos, uma questão restante*/
                        && !(questionsLeft.length === 1 && questionsLeft.first().empty())/*e ela não está vazia*/) {
                    var examPage = $('#exam-page-template > div').clone();
                    
                    examPage.find('.page-number').first().text(pageNumber++);
                    printDiv.append(examPage);
                    
                    console.time('columnize');
                    $('#exam-temp').columnize({
                        columns: 2,
                        target: '#' + printDiv.data('div-id') + ' .page:last .exam-content',
                        overflow: {
                            height: examPage.find('.exam-content').first().height(),
                            id: '#exam-temp',
                            doneFunc: function () {
                                console.timeEnd('columnize');
                                var questionsLeft = $('#exam-temp').find('.question-block');
                                
                                if (questionsLeft.length > 0 
                                        && !(questionsLeft.length === 1 && questionsLeft.first().empty())) { 
                                    setTimeout(buildExamLayout, 500, contentInfoBlock, printDiv, totalQuestions, callback);
                                     
                                    updateProgressBar(questionsLeft.length, totalQuestions, printDiv);      
                                } else if ($('#exam-temp').length > 0 && callback && typeof callback === 'function') {
                                    // atualiza a barra de progresso (100%)
                                    updateProgressBar(questionsLeft.length, totalQuestions, printDiv);

                                    // volta a barra de progresso para 0% após alguns segundos
                                    updateProgressBar(totalQuestions, totalQuestions, printDiv);
                                    // Remove as mensagens
                                    setLoadingText(contentInfoBlock, '');
                                    setAlertText(contentInfoBlock, '');

                                    // A div temporária é removida
                                    $('#exam-temp').remove();  

                                    // O callback é chamado
                                    callback();
                                }
                            }
                        }
                    });
                }
            }
            
            /*
             * 
             * @param {int} questionsLeft - questões restantes para o columnizer
             * @param {int} totalQuestions - total de questões da prova
             * @param {object} printDiv - DOM Object da div que será usada para impressão
             */
            function updateProgressBar(questionsLeft, totalQuestions, printDiv) {
                if (totalQuestions > 0) {
                    var percentage = 100 - ((questionsLeft * 100) / totalQuestions);
                    
                    printDiv.parent().find('.progress-bar').first().css('width', percentage + '%');
                }
            }
        };

        /*
         *  Carrega todas as questões da aplicação em suas respectivas provas
         * 
         */
        loadExams = function () {
            require(['app/pages/school-management/exam/prepare-content'],
            function (prepareContent) {
                $('.content-info-block').each(function () {
                    loadContent(
                        +$(this).data('content-id'), 
                        $(this).find('.content-questions').first(), 
                        false
                    );
                });
            });
        };

        /*
         * Gera o gabarito do simulado (.pdf e .csv) e abre a janela de 
         * impressão para o pdf
         * 
         */
        generateAnswerKey = function () {
            $('#answer-key-tables').html('');
            
            var CSVAnswers = "Numero,Resposta,Disciplina\n";
            var questions = $('.subject-block > .question-block, .parallel-subject-block > .question-block');
            addTable();
            questions.each(function() {
                var parentBlock = $(this).parent();
                var baseSubjectName = parentBlock.closest('.base-subject-block').data('name'); 
                var differentBgColor = false;
                
                if (parentBlock.hasClass('parallel-subject-block')) { 
                    if ($(this).prev('.question-block').length === 0) {
                        var subjectName = 'OPÇÃO ' + parentBlock.data('name');
                        addTitleRow(subjectName);
                    }
                    differentBgColor = true;
                } else if (parentBlock.prev('.subject-block').length === 0 && $(this).prev('.question-block').length === 0) {
                    //addTitleRow(baseSubjectName);                  
                }
                
                var questionNumber = $(this).find('.q-number').first().text();
                var correctAlternative = $(this).data('correct-alternative');
                addRow(questionNumber, correctAlternative, baseSubjectName, differentBgColor);
            });
            
            $('#answer-key-tables').css("height", "210mm");

            $("#save-answers-csv").removeAttr("disabled");
            $("#save-answers-csv").attr("href", 'data:attachment/csv,' +  encodeURIComponent(CSVAnswers));
            $("#save-answers-csv").attr("target", '_blank');
            $("#save-answers-csv").attr("download", 'gabarito.csv');
            
            
            /*
             * Adiciona uma tabela de respostas ao gabarito
             */
            function addTable() {
                $('#answer-key-tables').append(
                    '<div class="col-xs-3">' +
                        '<table class="table-striped answers-table col-xs-10 col-xs-offset-1">' + 
                            '<tbody></tbody>' +
                        '</table>' +
                    '</div>'
                );
            }
            
            /*
             * Adiciona uma linha, de uma única célula, à última tabela do gabarito.
             * O conteúdo da linha será um texto.
             * 
             * @param {String} subjectName - conteúdo da linha
             * 
             */
            function addTitleRow(subjectName) {
                var row = '<tr>' + 
                    '<td class="text-center text-bold" colspan="2">' + subjectName + '</td>'
                '</tr>';
        
                appendRow(row);
            }
            
            /*
             * Adiciona uma linha à última tabela do gabarito e ao .csv
             */
            function addRow(number, correctAlternative, baseSubjectName, differentBgColor) {
                var alternativeLetter = String.fromCharCode(correctAlternative + 'A'.charCodeAt(0));
                var rowClass = '';
                if (differentBgColor) {
                    rowClass = 'highlighted-row';
                }
                var row = '<tr class="' + rowClass + '">' +
                    '<td class="text-center col-xs-2">' + number + '</td>' + 
                    '<td class="text-center col-xs-2">' + alternativeLetter + '</td>' +
                '</tr>';
        
                appendRow(row);
                
                CSVAnswers += (number) + ',' + alternativeLetter + ',' + baseSubjectName + "\n";   
            }
            
            /*
             * Anexa a linha à última tabela do gabarito
             * 
             * @param {object} row - jQuery Object da linha a ser anexada
             */
            function appendRow(row) {
                if ($('.answers-table:last > tbody > tr').length > 22) {
                    addTable();
                }
        
                $('.answers-table:last > tbody').append(row);  
            }
        };

        return {
            init: function () {
                prepareApplicationListeners();
                loadExams();
            }
        };

    }());

    return prepareApplication;
});
