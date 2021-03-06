/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
//var $ = require('jquery');
import 'bootstrap';
import 'summernote/dist/summernote-bs4.js';
import 'codemirror';
import 'codemirror/mode/xml/xml.js';
import hljs from 'highlight.js/lib/highlight';
import javascript from 'highlight.js/lib/languages/javascript';
import xml from 'highlight.js/lib/languages/xml';
import css from 'highlight.js/lib/languages/css';
import php from 'highlight.js/lib/languages/php';
import twig from 'highlight.js/lib/languages/twig';


//WISYWIG

$(function() {   
        
    $('.summernote').summernote({
        
        height: 250,   
        codemirror: { // codemirror options
            theme: 'monokai',
        },
        styleTags: [
            'p',
            {title: 'Blockquote', tag: 'blockquote', className: 'blockquote', value: 'blockquote'},
            {title: 'Bloc Erreur', tag: 'blockquote', className: 'blocErreur', value: 'blockquote'},
            {title: 'Bloc Lien', tag: 'blockquote', className: 'blocLien', value: 'blockquote'},
            {title: 'Code', tag: 'pre', className: 'code', value: 'pre'},
            {title: 'Titre 1', tag: 'h1', className: 'titre1', value: 'h1'},
            {title: 'Titre 2', tag: 'h2', className: 'titre2', value: 'h2'},
            {title: 'Titre 3', tag: 'h3', className: 'titre3', value: 'h3'},
            {title: 'Titre 4', tag: 'h4', className: 'titre4', value: 'h4'},
            {title: 'Titre 5', tag: 'h5', className: 'titre5', value: 'h5'},
            {title: 'Titre 6', tag: 'h6', className: 'titre6', value: 'h6'},
          ]
    });


    hljs.registerLanguage('html', xml);
    hljs.registerLanguage('js', javascript);
    hljs.registerLanguage('css', css);
    hljs.registerLanguage('php', php);
    hljs.registerLanguage('twig', twig);
    

    $('.code').each(function(i, block) {
        hljs.configure({useBR: true});
        hljs.highlightBlock(block);
    });


//---------------------------FIN PARAMETRE WYSIWYG ET HIGHLIGHT-------------------------------

//---------------------------REQUETE AJAX SEARCHBAR-------------------------------------------

$('#form_query').on('keyup',delay(function(){
    var $form = $(this).closest('form');
    $.ajax({
        type: "POST",
        data: $form.serialize(),
        url: $form.attr('action'),
        success: function(data){
            var html = '';
            data.forEach(element => {
                html +='<a href="'+element.url+'"><li>'+element['title']+'</li></a>'
            });
            
            $('#resultSearch').html(html)
                    
        }
    })
},500));


//===================================
//      DELAY
//===================================
function delay(callback, ms) {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  }

//---------------------------FIN REQUETE AJAX SEARCHBAR-------------------------------------------




    var $followLink = $('.follow-link');

    // Pour chaque élément ".follow-link"
    $followLink.each(function(index, element) {
        // Intercepte l'événement "click" du lien
        $(element).click(function(e) {
            e.preventDefault(); // Annule le chargement de la page

            $.getJSON($(this).attr('href'), function(data) {
                if (data.success == true) {
                    if(data.isFollow) { // Si on suit le tuto
                        $( element ).children('i').addClass( 'text-primary' ).removeClass( 'text-dark' );
                        
                    } else {
                        $(element).children('i').addClass('text-dark').removeClass('text-primary');
                    }
                }
            });

        });
    });

    //NOTATION
	// On ajoute la classe "js" à la liste pour mettre en place par la suite du code CSS uniquement dans le cas où le Javascript est activé
	$("ul.notes-echelle").addClass("js");
	// On passe chaque note à l'état grisé par défaut
	$("ul.notes-echelle li").addClass("note-off");
	// Au survol de chaque note à la souris
	$("ul.notes-echelle li").mouseover(function() {
		// On passe les notes supérieures à l'état inactif (par défaut)
		$(this).nextAll("li").addClass("note-off");
		// On passe les notes inférieures à l'état actif
		$(this).prevAll("li").removeClass("note-off");
		// On passe la note survolée à l'état actif (par défaut)
        $(this).removeClass("note-off");
        
	});
	// Lorsque l'on sort du sytème de notation à la souris
	$("ul.notes-echelle").mouseout(function() {
		// On passe toutes les notes à l'état inactif
		$(this).children("li").addClass("note-off");
		// On simule (trigger) un mouseover sur la note cochée s'il y a lieu
		$(this).find("li input:checked").parent("li").trigger("mouseover");
	});


    $("ul.notes-echelle input")
        // Lorsque le focus est sur un bouton radio
        .focus(function() {
            // On supprime les classes de focus
            $(this).parents("ul.notes-echelle").find("li").removeClass("note-focus");
            // On applique la classe de focus sur l'item tabulé
            $(this).parent("li").addClass("note-focus");
            // [...] cf. code précédent
        })
        // Lorsque l'on sort du sytème de notation au clavier
        .blur(function() {
            // On supprime les classes de focus
            $(this).parents("ul.notes-echelle").find("li").removeClass("note-focus");
            // [...] cf. code précédent
        })
        // Lorsque la note est cochée
        .click(function() {
            // On supprime les classes de note cochée
            $(this).parents("ul.notes-echelle").find("li").removeClass("note-checked");
            // On applique la classe de note cochée sur l'item choisi
            $(this).parent("li").addClass("note-checked");
        });

    // On simule un survol souris des boutons cochés par défaut
    $("ul.notes-echelle input:checked").parent("li").trigger("mouseover");
    // On simule un click souris des boutons cochés
    $("ul.notes-echelle input:checked").trigger("click");

    // NOTE TUTO
    var $tutoNoteForm = $('#tuto-note');
    $tutoNoteForm.find('[name="notesA"]').change(function() {
        
        $.get($tutoNoteForm.attr('action'), $tutoNoteForm.serialize(), function() {

        });
    });
});


// Tableau de memorisation des notes pour chaque liste
var ArrListeEtoile = new Object();

//-------------------------------------------------------
// Selection d une note pour une liste
//-------------------------------------------------------
function ChoixSelection(idListe, indice, nbEtoile){
	ArrListeEtoile[idListe] = indice;
	var score = "score-" + idListe;
	document.getElementById(score).innerHTML = " " + indice + "/" + nbEtoile;
}

//-------------------------------------------------------
// Creation d une liste de notation unique
//-------------------------------------------------------
function CreateListeEtoile(idListe, nbEtoile){
	ArrListeEtoile[idListe] = 0;

	var renduListe = "";
	renduListe += "<div class=\"listeEtoile\" onmouseout=\"GestionHover('" + idListe + "', -1, '" + nbEtoile + "')\">";
	renduListe += "<ul>";
	
	for(i=1; i<=nbEtoile; i++){
		renduListe += "<li>";
		renduListe += "<a href=\"javascript:ChoixSelection('" + idListe + "', '" + i + "', '" + nbEtoile + "')\" onmouseover=\"GestionHover('" + idListe + "', '" + i + "', '" + nbEtoile + "')\">";
		renduListe += "<img id=\"staroff-" + idListe + "-" + i + "\" src=\"staroff.gif\" border=\"0\" title=\"" + i + "\" style=\"border-width: 0px; display: block;\">";
		renduListe += "<img id=\"staron-" + idListe + "-" + i + "\" src=\"staron.gif\" border=\"0\" title=\"" + i + "\" style=\"border-width: 0px; display: none;\">";
		renduListe += "</a>";
		renduListe += "</li>";
	}
	
	renduListe += "	</ul>";
	renduListe += "</div>";
	renduListe += "<label id=\"score-" + idListe + "\"></label>";
	
	document.getElementById(idListe).outerHTML = renduListe;
}

