import mitt from 'mitt'

export const emitter = mitt()

export function showErrorDialog(message) {
    emitter.emit('show-error-dialog', {message})
}

export function onErrorDialogShow() {
    return new Promise((resolve) => {
        emitter.on('show-error-dialog', (data) => {
            resolve(data)
        })
    })
}
