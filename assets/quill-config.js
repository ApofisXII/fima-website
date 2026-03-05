import Quill from 'quill';

const TOOLBAR_WITH_STYLES = [
    [{ header: [2, 3, false] }],
    ['bold', 'italic', 'underline'],
    [{ color: ['', '#18407E', '#9095BE', '#D62725', '#FBBB21'] }],
    [{ align: '' }, { align: 'center' }, { align: 'right' }],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['link'],
    ['clean']
];

const TOOLBAR_WITHOUT_STYLES = [
    ['bold', 'italic', 'underline'],
    [{ color: ['', '#18407E', '#9095BE', '#D62725', '#FBBB21'] }],
    [{ align: '' }, { align: 'center' }, { align: 'right' }],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['link'],
    ['clean']
];

const URL_REGEX = /(https?:\/\/[^\s]+|www\.[^\s]+)/g;

function linkUrlsInRange(quill, start, text) {
    URL_REGEX.lastIndex = 0;
    let match;
    while ((match = URL_REGEX.exec(text)) !== null) {
        const matchStart = start + match.index;
        const matchLength = match[0].length;
        const format = quill.getFormat(matchStart, matchLength);
        if (format.link) continue;
        const href = match[0].startsWith('http') ? match[0] : 'https://' + match[0];
        quill.formatText(matchStart, matchLength, 'link', href, 'api');
    }
}

function enableAutoLink(quill) {
    quill.on('text-change', function(delta, oldDelta, source) {
        if (source !== 'user') return;

        const isPaste = delta.ops.some(op => typeof op.insert === 'string' && op.insert.length > 1);

        if (isPaste) {
            linkUrlsInRange(quill, 0, quill.getText());
            return;
        }

        const lastInsert = delta.ops.find(op => op.insert);
        if (!lastInsert || (lastInsert.insert !== ' ' && lastInsert.insert !== '\n')) return;

        const selection = quill.getSelection();
        if (!selection) return;

        const [line] = quill.getLine(selection.index);
        if (!line) return;

        const lineStart = quill.getIndex(line);
        linkUrlsInRange(quill, lineStart, line.domNode.textContent);
    });
}

export function fimaInitQuill({ editors, withStyles = true }) {
    const toolbar = withStyles ? TOOLBAR_WITH_STYLES : TOOLBAR_WITHOUT_STYLES;

    return editors.map(({ editorId, inputId, placeholder }) => {
        const quill = new Quill(`#${editorId}`, {
            modules: { toolbar },
            placeholder: placeholder || '',
            theme: 'snow'
        });
        enableAutoLink(quill);
        return { quill, inputId };
    });
}

export function fimaQuillSync(instances) {
    instances.forEach(({ quill, inputId }) => {
        document.getElementById(inputId).value = quill.root.innerHTML;
    });
}
