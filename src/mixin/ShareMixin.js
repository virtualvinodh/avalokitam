const AI_BACKEND = process.env.AI_BACKEND || ''

export const ShareMixin = {
  data () {
    return {
      _poemId: null,
      _poemVerse: null
    }
  },
  methods: {
    async _ensureSaved (verse) {
      if (this._poemId && this._poemVerse === verse) return this._poemId
      const resp = await fetch(AI_BACKEND + '/compositions', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ verse: verse.trim() })
      })
      const { id } = await resp.json()
      this._poemId = id
      this._poemVerse = verse
      return id
    },
    _poemUrl (id) {
      return window.location.origin + '/poem/' + id
    },
    async saveAndCopy (verse) {
      this.saving = true
      try {
        const id = await this._ensureSaved(verse)
        const url = this._poemUrl(id)
        const notify = () => this.$q.notify({
          message: 'சேமிக்கப்பட்டது! இணைப்பு நகலெடுக்கப்பட்டது.',
          color: 'grey-8',
          position: 'top',
          timeout: 3000,
          actions: [{ label: 'திற', color: 'white', handler: () => this.$router.push('/poem/' + id) }]
        })
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(url).then(notify).catch(() => { this._fallbackCopy(url); notify() })
        } else {
          this._fallbackCopy(url)
          notify()
        }
      } catch (e) {
        this.$q.notify({ message: 'சேமிக்க இயலவில்லை. மீண்டும் முயற்சிக்கவும்.', color: 'red-7', position: 'top' })
      } finally {
        this.saving = false
      }
    },
    _fallbackCopy (text) {
      const el = document.createElement('textarea')
      el.value = text
      el.style.position = 'fixed'
      el.style.opacity = '0'
      document.body.appendChild(el)
      el.select()
      document.execCommand('copy')
      document.body.removeChild(el)
    },
    async shareX (verse) {
      const id = await this._ensureSaved(verse)
      const lines = verse.split('\n').join(' | ')
      window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(lines) + '&url=' + encodeURIComponent(this._poemUrl(id)), '_blank', 'noopener')
    },
    async shareFacebook (verse) {
      const id = await this._ensureSaved(verse)
      window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(this._poemUrl(id)), '_blank', 'noopener')
    },
    async shareInstagram (verse) {
      const blob = await this._verseToImage(verse)
      const file = new File([blob], 'venpaa.png', { type: 'image/png' })
      if (navigator.canShare && navigator.canShare({ files: [file] })) {
        await navigator.share({ files: [file], title: 'என் வெண்பா' })
      } else {
        const a = document.createElement('a')
        a.href = URL.createObjectURL(blob)
        a.download = 'venpaa.png'
        a.click()
        URL.revokeObjectURL(a.href)
        this.$q.notify({ message: 'படம் பதிவிறக்கப்பட்டது — Instagram-ல் பதிவிடவும்!', color: 'grey-8', position: 'top', timeout: 3000 })
      }
    },
    _verseToImage (verse) {
      return new Promise((resolve) => {
        const W = 1080, H = 1080
        const canvas = document.createElement('canvas')
        canvas.width = W
        canvas.height = H
        const ctx = canvas.getContext('2d')

        const grad = ctx.createLinearGradient(0, 0, W, H)
        grad.addColorStop(0, '#1a1a2e')
        grad.addColorStop(1, '#16213e')
        ctx.fillStyle = grad
        ctx.fillRect(0, 0, W, H)

        ctx.strokeStyle = 'rgba(255,255,255,0.12)'
        ctx.lineWidth = 2
        ctx.strokeRect(40, 40, W - 80, H - 80)

        ctx.fillStyle = '#f5f0e8'
        ctx.textAlign = 'center'
        const lines = verse.split('\n')
        const fontSize = lines.length <= 2 ? 52 : lines.length <= 4 ? 44 : 36
        ctx.font = `${fontSize}px "Mukta Malar", serif`
        const lineH = fontSize * 1.8
        const totalH = lines.length * lineH
        const startY = (H - totalH) / 2 + fontSize

        lines.forEach((line, i) => {
          ctx.fillText(line.trim(), W / 2, startY + i * lineH)
        })

        ctx.fillStyle = 'rgba(255,255,255,0.35)'
        ctx.font = '24px "Mukta Malar", sans-serif'
        ctx.fillText('அவலோகிதம் · avalokitam.app', W / 2, H - 60)

        canvas.toBlob(blob => resolve(blob), 'image/png')
      })
    }
  }
}
