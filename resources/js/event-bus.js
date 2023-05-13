import mitt from 'mitt'

export const FILE_UPLOAD_STARTED = 'upload-started'
export const SHOW_ERROR_DIALOG = 'show_error_dialog'

export const emitter = mitt()

export function showErrorDialog(message) {
    emitter.emit(SHOW_ERROR_DIALOG, {message})
}
