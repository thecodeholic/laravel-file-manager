export function isImage(file) {
    return /^image\/\w+$/.test(file.mime)
}

export function isPDF(file) {
    return [
        'application/pdf',
        'application/x-pdf',
        'application/acrobat',
        'application/vnd.pdf',
        'text/pdf',
        'text/x-pdf',
    ].includes(file.mime)
}

export function isAudio(file) {
    return ['audio/mpeg',
        'audio/ogg',
        'audio/wav',
        'audio/x-m4a',
        'audio/webm',
    ].includes(file.mime)
}

export function isVideo(file) {
    return [
        'video/mp4',
        'video/mpeg',
        'video/ogg',
        'video/quicktime',
        'video/webm',
    ].includes(file.mime)
}

export function isWord(file) {
    return [
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-word.document.macroEnabled.12',
        'application/vnd.ms-word.template.macroEnabled.12',
    ].includes(file.mime)
}

export function isExcel(file) {
    return [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel.sheet.macroEnabled.12',
        'application/vnd.ms-excel.template.macroEnabled.12',
    ].includes(file.mime)
}

export function isZip(file) {
    return [
        'application/zip',
    ].includes(file.mime)
}

export function isText(file) {
    return [
        'text/plain',
        'text/html',
        'text/css',
        'text/javascript',
        'text/csv',
    ].includes(file.mime)
}
