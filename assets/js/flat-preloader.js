const sleep = timeout => new Promise(resolve => setTimeout(resolve, timeout))

const $overlay = document.getElementById('flat-preloader-overlay');

window.addEventListener('load', async function () {
  const delayTime = flatPreloader.delayTime

  await sleep(delayTime)
  document.body.classList.remove('flat-preloader-active')
  $overlay.classList.add('hide')
  await sleep(300)
  $overlay.remove()
})
