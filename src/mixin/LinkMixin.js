var parseString = require('xml2js').parseString

export const LinkMixin = {
  data () {
    return {
      apiCall: this.$axios.create({
        baseURL: 'http://localhost',
        timeout: 100000
      }),
      vowels: 'அஆஇஈஉஊஎஏஐஒஓஔ'.split(''),
      consonants: 'கஙசஜஞடணதநனபமயரறலளழவஷஸஹ'.split(''),
      consonantsV: 'கஙசஞடணதநனபமயரறலளழவ'.split(''),
      vowelSigns: 'ாிீுூெேைொோௌ்'.split(''),
      vowelSignV: 'ாிீுூெேைொோௌ'.split(''),
      shortV: 'அஇஉஎஒ'.split(''),
      shortVaiau: 'அஇஉஎஒஐஔ'.split(''),
      shortVS: 'ிுெொ'.split(''),
      shortVSaiau: 'ிுெொைௌ'.split(''),
      longV: 'ஆஈஊஏஓ'.split(''),
      longVS: 'ாீூேோ'.split(''),
      aiauVS: 'ைௌ'.split(''),
      aiauV: 'ஐஔ'.split(''),
      pulli: '்'.split(''),
      aytham: ['ஃ'],
      verseTypes: [
        {
          label: 'வெண்பா',
          value: 'venpaa'
        },
        {
          label: 'ஆசிரியப்பா',
          value: 'aciriyappaa'
        },
        {
          label: 'தரவுகொச்சகக் கலிப்பா',
          value: 'kalippaa'
        },
        {
          label: 'வெண்கலிப்பா',
          value: 'venkalippaa'
        },
        {
          label: 'வஞ்சிப்பா',
          value: 'vanjippaa'
        },
        {
          label: 'குறட்டாழிசை',
          value: 'kuRa_TTAZicY'
        },
        {
          label: 'குறள்வெண்செந்துறை',
          value: 'kuRa_Lve_Nce_ntuRY'
        },
        {
          label: 'வெண்டாழிசை',
          value: 've_NTAZicY'
        },
        {
          label: 'வெள்ளொத்தாழிசை',
          value: 've_LLo_ttAZicY'
        },
        {
          label: 'வெண்டுறை',
          value: 've_NTuRY'
        },
        {
          label: 'வெளிவிருத்தம்',
          value: 'veLiviru_tta_m'
        },
        {
          label: 'ஆசிரியத்தாழிசை',
          value: '_Aciriya_ttAZicY'
        },
        {
          label: 'ஆசிரியத்துறை',
          value: '_Aciriya_ttuRY'
        },
        {
          label: 'ஆசிரியவிருத்தம்',
          value: '_Aciriyaviru_tta_m'
        },
        {
          label: 'கலித்தாழிசை',
          value: 'kali_ttAZicY'
        },
        {
          label: 'கலித்துறை',
          value: 'kali_ttuRY'
        },
        {
          label: 'கட்டளைக்கலிப்பா',
          value: 'ka_TTaLY_kkali_ppA'
        },
        {
          label: 'கட்டளைக்கலித்துறை',
          value: 'ka_TTaLY_kkali_ttuRY'
        },
        {
          label: 'கலிவிருத்தம்',
          value: 'kaliviru_tta_m'
        },
        {
          label: 'வஞ்சித்தாழிசை',
          value: 'va_Jci_ttAZicY'
        },
        {
          label: 'வஞ்சித்துறை',
          value: 'va_Jci_ttuRY'
        },
        {
          label: 'வஞ்சிவிருத்தம்',
          value: 'va_Jciviru_tta_m'
        }
      ]
    }
  },
  computed: {
    syllables: function () {
      var syllableList = []
      var dhis = this
      this.consonants.forEach(function (cons) {
        dhis.vowelSigns.forEach(function (vowe) {
          syllableList.push(cons + vowe)
        })
      })
      return syllableList
    }
  },
  methods: {
    lineCountGet: function (verse) {
      var text = verse
      text = text.replace(/ - /g, ' ')
      text = text.replace(/- /g, ' ')
      text = text.replace(/ -/g, ' ')
      text = text.replace(/-/g, ' ')
      return text.split('\n').filter(x => x !== '').map(x => x.trim().split(' ').length)
    },
    closestType: function (text, verseResult) {
      var resultCheck = []

      this.verseTypes.forEach(function (verseType) {
        // console.log(verseType)
        var totalCount = verseResult[verseType.value][0].Rule.length
        var passCount = 0

        verseResult[verseType.value][0].Result.forEach(function (result, index) {
          if (result === '1') {
            passCount = passCount + 1
          }
          if (result === 'info') {
            totalCount = totalCount - 1
          }
          if (verseResult[verseType.value][0].Rule[index] === 'பொருத்தம்') {
            totalCount = totalCount - 1
          }
        })

        resultCheck.push([verseType, passCount / totalCount])
      })

      // console.log(resultCheck)

      resultCheck = resultCheck.filter(x => x[1] >= 0.5).sort((a, b) => b[1] - a[1])

      // console.log(resultCheck)

      resultCheck = resultCheck.map(x => x[0])
      if (this.lineCountGet(text).length === 1) {
        return []
      } else if (this.lineCountGet(text).length === 2) {
        return [{
          label: 'குறட்டாழிசை',
          value: 'kuRa_TTAZicY'
        },
        {
          label: 'குறள் வெண்செந்துறை',
          value: 'kuRa_Lve_Nce_ntuRY'
        },
        {
          label: 'கலித்தாழிசை',
          value: 'kali_ttAZicY'
        }]
      } else if (this.lineCountGet(text).length === 3) {
        return resultCheck.concat([{
          label: 'ஆசிரியத் தாழிசை',
          value: '_Aciriya_ttAZicY'
        }])
      } else {
        return resultCheck
      }
    },
    sleep: function (ms) {
      return new Promise(resolve => setTimeout(resolve, ms))
    },
    vepaLastWordClass: function (result) {
      return result['verse']['VenpaLastWordClass'][0]
    },
    lettersGet: function (result) {
      return result['verse']['Letter'][0].$
    },
    ornamentsGet: function (result) {
      var ornaments = {}
      var alliteration = {}
      var rhyme = {}
      var ultimarhyme = {}
      var footInd
      var dhis = this
      if (typeof result['verse'] !== 'undefined') {
        result['verse']['MetricalLine'].forEach(function (line, li) {
          if (typeof line.Ornamentation !== 'undefined') {
            line.Ornamentation.forEach(function (ornament) {
              if (typeof ornament.Alliteration !== 'undefined') {
                var rhymeArr2 = []

                ornament.Alliteration.forEach(function (rhymeEntry) {
                  var rhymeTemp = []
                  rhymeTemp.push(rhymeEntry.$.type)
                  rhymeTemp.push(rhymeEntry.$.class)
                  footInd = []
                  rhymeEntry.Match.forEach(function (foot) {
                    footInd.push(foot.$.foot)
                  })
                  rhymeTemp.push(footInd)
                  rhymeArr2.push(rhymeTemp)
                })

                alliteration[li] = dhis.filterRhymes(rhymeArr2)
              }

              if (typeof ornament.Rhyme !== 'undefined') {
                var rhymeArr = []

                ornament.Rhyme.forEach(function (rhymeEntry) {
                  var rhymeTemp = []
                  rhymeTemp.push(rhymeEntry.$.type)
                  rhymeTemp.push(rhymeEntry.$.class)
                  footInd = []
                  rhymeEntry.Match.forEach(function (foot) {
                    footInd.push(foot.$.foot)
                  })
                  rhymeTemp.push(footInd)
                  rhymeArr.push(rhymeTemp)
                })

                rhyme[li] = dhis.filterRhymes(rhymeArr)
              }

              if (typeof ornament['Ultima-Rhyme'] !== 'undefined') {
                let ultimarhymeli = []
                ultimarhymeli.push(ornament['Ultima-Rhyme'][0].$.type)
                ultimarhymeli.push('')

                footInd = []
                ornament['Ultima-Rhyme'][0].Match.forEach(function (foot) {
                  footInd.push(foot.$.foot)
                })
                ultimarhymeli.push(footInd)

                ultimarhyme[li] = [ultimarhymeli]
              }
            })
          }
        })
        var ornamentLine = {}

        if (typeof result['verse']['Ornamentation'] !== 'undefined') {
          if (typeof result['verse']['Ornamentation'][0].Alliteration !== 'undefined') {
            ornamentLine['alliteration'] = []
            result['verse']['Ornamentation'][0].Alliteration.forEach(function (rhymeEntry) {
              let rhymeTemp = []
              rhymeEntry.Match.forEach(function (foot) {
                rhymeTemp.push(foot.$.line)
              })
              // console.log(rhymeEntry)
              ornamentLine['alliteration'].push([rhymeEntry.$.class, rhymeTemp])
            })
            // console.log(ornamentLine['alliteration'])
            ornamentLine['alliteration'] = this.filterRhymesLine(ornamentLine['alliteration'])
          }

          if (typeof result['verse']['Ornamentation'][0].Rhyme !== 'undefined') {
            ornamentLine['rhyme'] = []
            // console.log(result['verse']['Ornamentation'][0].Rhyme)
            result['verse']['Ornamentation'][0].Rhyme.forEach(function (rhymeEntry) {
              let rhymeTemp = []
              rhymeEntry.Match.forEach(function (foot) {
                rhymeTemp.push(foot.$.line)
              })
              ornamentLine['rhyme'].push([rhymeEntry.$.class, rhymeTemp])
            })

            ornamentLine['rhyme'] = this.filterRhymesLine(ornamentLine['rhyme'])
          }

          if (typeof result['verse']['Ornamentation'][0]['Ultima-Rhyme'] !== 'undefined') {
            let tmpur = []
            result['verse']['Ornamentation'][0]['Ultima-Rhyme'][0].Match.forEach(function (foot) {
              tmpur.push(foot.$.line)
            })

            ornamentLine['ultimarhyme'] = [['', tmpur]]
          }
        }
      }
      var ornamentsFoot = {}
      ornamentsFoot['alliteration'] = alliteration
      ornamentsFoot['rhyme'] = rhyme
      ornamentsFoot['ultimarhyme'] = ultimarhyme

      ornaments['foot'] = ornamentsFoot
      ornaments['line'] = ornamentLine

      return ornaments
    },
    filterRhymes: function (rhymeArr) {
      let positions = [...new Set(rhymeArr.map(x => JSON.stringify(x[2])))]

      let rhymeli = []

      positions.forEach(function (pos) {
        let classs = []
        let type = {}
        let classNm
        rhymeArr.forEach(function (rhymeEntry) {
          if (JSON.stringify(rhymeEntry[2]) === pos) {
            classs.push(rhymeEntry[1])
          }
          type[rhymeEntry[1]] = rhymeEntry[0]
        })

        if (classs.includes('special')) {
          classNm = 'special'
        } else if (classs.includes('varga')) {
          classNm = 'varga'
        } else if (classs.includes('inam')) {
          classNm = 'inam'
        } else if (classs.includes('acitai')) {
          classNm = 'acitai'
        } else if (classs.includes('uyir')) {
          classNm = 'uyir'
        } else if (classs.includes('nedil')) {
          classNm = 'nedil'
        }

        rhymeli.push([type[classNm], classNm, JSON.parse(pos)])
      })

      /* var vargalength
      var speciallength

      rhymeli.forEach(function (entry) {
        if (entry[1] === 'varga') {
          vargalength = entry[2].length
        }
        if (entry[1] === 'special') {
          speciallength = entry[2].length
        }
      })

      if (vargalength > speciallength) {
        rhymeli = rhymeli.filter(x => x[1] !== 'special')
      } */

      return rhymeli
    },
    filterRhymesLine: function (rhymeArr) {
      let positions = [...new Set(rhymeArr.map(x => JSON.stringify(x[1])))]

      let rhymeli = []

      positions.forEach(function (pos) {
        let classs = []
        let classNm
        rhymeArr.forEach(function (rhymeEntry) {
          if (JSON.stringify(rhymeEntry[1]) === pos) {
            classs.push(rhymeEntry[0])
          }
        })

        if (classs.includes('special')) {
          classNm = 'special'
        } else if (classs.includes('varga')) {
          classNm = 'varga'
        } else if (classs.includes('inam')) {
          classNm = 'inam'
        } else if (classs.includes('acitai')) {
          classNm = 'acitai'
        } else if (classs.includes('uyir')) {
          classNm = 'uyir'
        } else if (classs.includes('nedil')) {
          classNm = 'nedil'
        }

        rhymeli.push([classNm, JSON.parse(pos)])
      })

      /* var vargalength
      var speciallength

      rhymeli.forEach(function (entry) {
        if (entry[0] === 'varga') {
          vargalength = entry[1].length
        }
        if (entry[0] === 'special') {
          speciallength = entry[1].length
        }
      })

      if (vargalength > speciallength) {
        rhymeli = rhymeli.filter(x => x[0] !== 'special')
      } */

      return rhymeli
    },
    linetypesGet: function (result) {
      var types = []
      if (typeof result['verse'] !== 'undefined') {
        result['verse']['MetricalLine'].forEach(function (line) {
          types.push(line.$.type)
        })
      }
      return types
    },
    metricalFeetGet: function (result) {
      var verse = []
      if (typeof result['verse'] !== 'undefined') {
        result['verse']['MetricalLine'].forEach(function (line) {
          var ln = []
          line['MetricalFoot'].forEach(function (foot) {
            var ft = []
            foot['Metreme'].forEach(function (metreme) {
              ft.push([metreme._, metreme.$.type])
            })
            ln.push([ft, foot.$.class])
          })
          verse.push(ln)
        })
        return verse
      } else {
        return []
      }
    },
    linkageGet: function (result) {
      var verse = []
      if (typeof result['verse'] !== 'undefined') {
        result['verse']['MetricalLine'].forEach(function (line, li) {
          var ln = []
          line['MetricalFoot'].forEach(function (foot, mi) {
            var ft = []
            if (typeof foot.$.linkage !== 'undefined') {
              foot['Metreme'].forEach(function (metreme, mi) {
                if (mi === 0) {
                  ft = metreme.$.type
                }
              })
              var footclass = ''
              if (mi !== 0) {
                // console.log('here')
                footclass = line['MetricalFoot'][mi - 1].$.class
              } else {
                // console.log('here2')
                var len = result['verse']['MetricalLine'][li - 1]['MetricalFoot'].length - 1
                // console.log(len)
                footclass = result['verse']['MetricalLine'][li - 1]['MetricalFoot'][len].$.class
              }
              ln.push([footclass, ft, foot.$.linkage])
            } else {
              ln.push(['', '', ''])
            }
          })
          verse.push(ln)
        })
        return verse
      } else {
        return []
      }
    },
    getRandomInt: function (min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min
    },
    getJson: function (resultXML) {
      return new Promise(resolve => {
        parseString(resultXML, function (err, result) {
          resolve(result)
          err = 'error'
          // console.log(err)
        })
      })
    },
    convertAsync: function (text) {
      return new Promise(resolve => {
        var data = {
          'verse': text
        }
        this.apiCall.post('/api.php?verse=' + text.replace(/\n/g, '@@@@') + '', data)
          .then(function (response) {
            // // console.dirxml(response.data)
            resolve(response.data)
          })
          .catch(function (error) {
            error = 'error'
            // console.log(error)
          })
      })
    },
    half: function (number) {
      var str = '' + number
      str = str.split('.')
      if (str.length > 1) {
        return str[0] + '½'
      } else {
        return str[0]
      }
    },
    countSyllableWithout: function (word) {
      var countPulli = 0
      var dhis = this

      this.syllabize(word).forEach(function (syllable) {
        if (syllable.includes(dhis.pulli[0])) {
          countPulli += 1
        }
      })

      return this.syllabize(word).length - countPulli
    },
    countMatra: function (word) {
      var matra = 0
      var dhis = this
      var count = this.syllabize(word).length - 1

      this.syllabize(word).forEach(function (syll, index) {
        if (dhis.vowels.includes(syll)) {
          if (dhis.shortV.includes(syll)) {
            matra = matra + 1
          } else if (dhis.longV.includes(syll)) {
            matra = matra + 1
          } else if (dhis.aiauV.includes(syll)) {
            if (index === 0 && index === count) {
              matra = matra + 2
            } else if (index === 0) {
              matra = matra + 1.5
            } else {
              matra = matra + 1
            }
          }
        } else if (dhis.consonants.includes(syll)) {
          matra = matra + 1
        } else if (dhis.aytham.includes(syll)) {
          matra = matra + 0.5
        } else if (dhis.syllables.includes(syll)) {
          let sign = syll[1]

          if (dhis.pulli.includes(sign)) {
            matra = matra + 0.5
          } else if (dhis.shortVS.includes(sign)) {
            matra = matra + 1
          } else if (dhis.longVS.includes(sign)) {
            matra = matra + 2
          } else if (dhis.aiauVS.includes(sign)) {
            if (index === 0 && index === count) {
              matra = matra + 2
            } else if (index === 0) {
              matra = matra + 1.5
            } else {
              matra = matra + 1
            }
          }
        }
      })

      return matra
    },
    syllabize: function (word) {
      var voweAll = this.syllables.concat(this.consonants, this.vowels).sort(function (a, b) { return b.length - a.length })
      var regexRep = new RegExp('(' + voweAll.join('|') + ')', 'g')
      return word.replace(regexRep, '$1 ').trim().split(' ')
    },
    shortFeetType: function (name) {
      if (name.includes('பூ')) {
        return 'பூ'
      } else if (name.includes('ழல்')) {
        return 'நிழல்'
      } else if (name.includes('கனி')) {
        return 'கனி'
      } else if (name.includes('காய்')) {
        return 'காய்'
      } else if (name.includes('மா')) {
        // console.log('here')
        return 'மா'
      } else if (name.includes('விளம்')) {
        return 'விளம்'
      }
      return ''
    },
    metremeTypeStyle: function (name) {
      if (name === 'நேர்') {
        return 'text-blue-10'
      } else if (name === 'நிரை') {
        return 'text-deep-orange-10'
      }
    },
    lineTypeStyle: function (name) {
      if (name.includes('தனிச்சொல்')) {
        return 'text-teal-4'
      } else if (name.includes('குறளடி')) {
        return 'text-teal-6'
      } else if (name.includes('சிந்தடி')) {
        return 'text-teal-8'
      } else if (name.includes('அளவடி')) {
        return 'text-teal-10'
      } else if (name.includes('கழிநெடிலடி')) {
        return 'text-cyan-9'
      } else if (name.includes('நெடிலடி')) {
        return 'text-cyan-8'
      }
    },
    feetTypeStyle: function (name) {
      if (name.includes('பூ')) {
        return 'text-pink-10'
      } else if (name.includes('ழல்')) {
        return 'text-blue-grey-10'
      } else if (name.includes('கனி')) {
        return 'text-deep-orange-7'
      } else if (name.includes('காய்')) {
        return 'text-green-10'
      } else if (name.includes('மா')) {
        return 'text-yellow-10'
      } else if (name.includes('விளம்')) {
        return 'text-purple-6'
      }
    },
    linkTypeStyle: function (name) {
      var shortName = ''
      if (name.includes('வெண்டளை')) {
        return 'text-blue-grey-5'
      } else if (name.includes('ஆசிரியத்தளை')) {
        return 'text-brown-5'
      } else if (name.includes('வஞ்சித்தளை')) {
        return 'text-indigo-5'
      } else if (name.includes('கலித்தளை')) {
        return 'text-cyan-7'
      }
      return shortName
    },
    shortLinkType: function (name) {
      var shortName = ''
      if (name === 'இயற்சீர் வெண்டளை') {
        return 'இ. வெ'
      } else if (name === 'வெண்சீர் வெண்டளை') {
        return 'வெ. வெ'
      } else if (name === 'நேரொன்றிய ஆசிரியத்தளை') {
        return 'நே. ஆ'
      } else if (name === 'நிரையொன்றிய ஆசிரியத்தளை') {
        return 'நி. ஆ'
      } else if (name === 'ஒன்றிய வஞ்சித்தளை') {
        return 'ஒ.றி. வ'
      } else if (name === 'ஒன்றா வஞ்சித்தளை') {
        return 'ஒ.றா. வ'
      } else if (name === 'கலித்தளை') {
        return 'கலி.'
      }
      return shortName
    }
  }
}
