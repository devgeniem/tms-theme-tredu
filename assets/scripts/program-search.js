/* eslint-disable no-console */
/**
 * Functionality of program search
 */

// Use jQuery as $ within this file scope.
const $ = jQuery; // eslint-disable-line no-unused-vars

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
                // console.log( button.querySelector( 'input[type="hidden"]' ) );
                button.querySelector( 'input[type="hidden"]' ).setAttribute( 'disabled', 'disabled' );
                form.submit();
            } );

        } );

    }

    docReady() {
        this.programSearch();
    }

}
