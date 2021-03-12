console.log()

const range = document.querySelector('#yearRange')
const form = document.querySelector('#yearForm')

range.addEventListener('mouseup', () =>
{
    form.submit()
})