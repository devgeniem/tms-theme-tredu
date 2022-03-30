/* eslint-disable no-console */
/**
 * Functionality of program search
 */

// Use jQuery as $ within this file scope.
const $ = jQuery; // eslint-disable-line no-unused-vars
import 'jquery-ui/ui/widgets/autocomplete';

export default class ProgramSearch {

    programSearch() {
        const form = document.querySelector( '#program-search' );
        const selectElements = document.querySelectorAll( '#program-search select.search-filter' );
        selectElements.forEach( ( selectElem ) => {
            selectElem.addEventListener( 'change', () => {
                form.submit();
            } );

        } );

        const removeFilterBtns = document.querySelectorAll( '#program-search .remove-filter' );
        removeFilterBtns.forEach( ( button ) => {
            button.addEventListener( 'click', () => {
                button.querySelector( 'input[type="hidden"]' ).setAttribute( 'disabled', 'disabled' );
                form.submit();
            } );

        } );

    }

    autocompleteOnInput() {

        const wordList = JSON.parse( $( '#program-search-words' ).html() );
        console.log( 'wordList' );
        console.log( wordList );

        $( '#program-search input[type="search"]' )
            .autocomplete( {
                minLength: 1,
                source: wordList,
                // autoFocus: true,
                classes: {
                    // eslint-disable-next-line max-len
                    'ui-autocomplete': 'has-background-white has-border-primary has-border-2 is-absolute is-unstyled p-3 has-text-weight-semibold',
                },
            } );
    }

    docReady() {
        this.programSearch();
        this.autocompleteOnInput();
    }

}
