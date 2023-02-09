const coreBlocksArray = [ 'core/heading', 'core/paragraph', 'core/embed', 'core/list', 'core/quote', 'core/image', 'core/shortcode', 'core/table', 'core/file' ];

function addParentAttribute( settings, name ) {
    if ( !coreBlocksArray.includes(name) ) {
        return settings;
    }
 
    return Object.assign(settings, {
        parent: [
            'acf/uams-section',
         ],
    } );
}
 
wp.hooks.addFilter(
    'blocks.registerBlockType',
    'my-plugin/class-names/list-block',
    addParentAttribute
);