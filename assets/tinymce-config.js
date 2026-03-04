export function fimaInitTinymce(options) {
    const { fontUrl, ...rest } = options;
    tinymce.init({
        menubar: false,
        toolbar_mode: 'wrap',
        plugins: 'link | lists | paste',
        branding: false,
        paste_as_text: true,
        statusbar: false,
        color_map: [
            '000000', 'Nero',
            '18407E', 'Blu FIMA',
            '9095BE', 'Blu chiaro',
            'D62725', 'Rosso',
            'FBBB21', 'Giallo',
        ],
        color_cols: 5,
        custom_colors: false,
        content_style: `@font-face { font-family: "Sofia Sans"; src: url("${fontUrl}") format("truetype"); font-weight: 100 900; } body { font-family: "Sofia Sans", sans-serif; background-color: #F3F5F9; } body[data-mce-placeholder]:not(.mce-visualblocks)::before { opacity: 0.5; color: #999; font-size: 0.9rem; }`,
        ...rest,
    });
}
