/**
 * @package     SynergyPress
 * @version     1.6.0
 */

/**
 * Instantiate the Form Synergy class
 */
( function( undefined ) {
    'use-strict';
 
   /**
    * Import and prepare localized formSynergy object.
    * NOTE: Once imported, the formSynergy object is 
    * immediately removed. 
    * 
    * We can retrieve the data by using 
    * FS.prepared( key, 'all' );
    *
    **/ 
    formSynergy && FS.prepare( formSynergy );
 
    /**
     * This function will query all fs-trigger-* class names 
     * and create a related data-tag.
     */
    FS.createMethod( 'createTagRelation', () => {
        let modules = FS.prepared( 'importModules', 'all' );
        for( let module of modules ) {
            if( module ) {
                let el = document.querySelector( module.fstrigger );
                if( el && module.related ) {
                    el.setAttribute( module.related.attribute, module.related.value );
                }
            }
        }
    });
 
    /**
     * --- Only in advanced mode ---
     * This function will query all fs-placement-* class names 
     * and create options and placement tags.
     */
    FS.createMethod( 'applyPositions', () => {
        let positions = FS.prepared( 'customPlacements', 'all' );
        for( let pos of positions ) {
			let el = document.querySelector( pos.fsplacement );
            if( el ) {
                for (let [k, v] of Object.entries( pos.options ) ) {
                    el.setAttribute( 
                        'data-fs-' + k, 
                        "object" === typeof v
                            ? JSON.stringify( v )
                            : v 
                    );
                }
            }
        }
    });
    /**
     *! NOTE: Please do not uncomment this method.
     * FS.engage() is called
     * in: /public/fs-plugin.php
     */
})( this );