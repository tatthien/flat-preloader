flatPreloaderInit()

function flatPreloaderInit() {
  const sleep = timeout => new Promise(resolve => setTimeout(resolve, timeout))
  const overlayEl = document.getElementById('flat-preloader-overlay')
  const showPreloaderInstantly = flatPreloader.showPreloaderInstantly === '1' ? true : false

  // Fix error loading screen does not disappear when clicking on back button
  window.onpageshow = function(event) {
    if (event.persisted) {
      window.location.reload()
    }
  };

  window.addEventListener('load', async function () {
    if (!overlayEl) {
      return
    }

    const delayTime = flatPreloader.delayTime

    await sleep(delayTime)

    document.body.classList.remove('flat-preloader-active')
    overlayEl.classList.add('hide')

    await sleep(300)

    // Show the preloader immediately
    if (showPreloaderInstantly) {
      document.querySelectorAll('a').forEach(link => {
        if (link.getAttribute('data-fp-ignore') === 'true') {
          return
        }

        link.addEventListener('click', e => {
          let href = link.getAttribute('href')
          if (isIgnored(href)) {
            return
          }
          e.preventDefault()
          document.body.classList.add('flat-preloader-active')
          overlayEl.classList.remove('hide')
          window.location.href = href
        })
      })
    }
  })

  function isIgnored(url) {
    if (url.startsWith('http') && !url.includes(flatPreloader.host)) {
      return true
    }
    let ignore = false
    flatPreloader.ignores.forEach(regex => {
      if (new RegExp(regex).test(url)) {
        ignore = true
        return
      }
    })
    return ignore
  }
}
