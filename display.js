Vue.component("quizz-hebdo", {
    props: ["dataRss", "dataName", "dataUrl", "styleNeeded"],
    mounted() {
      if(this.dataRss) {
        dataParse = JSON.parse(this.dataRss);
        const tempData = dataParse.channel.item;

        for(let element of tempData) {
          const idNumber = Math.floor(Math.random()*90000) + 10000;
          const oldQuestion="document.getElementById('page-fiche').classList.add('quizz-open'); return false;"
          const newQuestion = "document.getElementById('reponse"+idNumber+"').classList.add('quizz-open');document.getElementById('reponse"+idNumber+"').classList.remove('quizz-close');document.getElementById('rna"+idNumber+"').classList.add('quizz-close');document.getElementById('rna"+idNumber+"').classList.remove('quizz-open');return false;"
          const oldTarget='href="#reponse"'
          const newTarget ='href="#reponse'+idNumber+'"'
          const oldClass="reponse-quizz-in"
          const newClass = "quizz-close"
          const oldID='id="reponse"'
          const newID='id="reponse'+idNumber+'"'
          const oldID2="rna"
          const newID2='rna'+idNumber
          const replacement = element.description.replaceAll(oldTarget,newTarget).replaceAll(oldID,newID).replaceAll(oldID2,newID2).replaceAll(oldQuestion,newQuestion).replaceAll(oldClass,newClass)
          element.description = replacement
        };
        this.goodData = tempData
      }
    },
    data() {
      return { goodData: "" };
    },
    template:
      '<div> <div class="quizz-hebdo-item" v-for="(item,index) in goodData" :key="index"> <div class="quizz-hebdo-item-day">{{item.jour}}</div> <div class="quizz-hebdo-item-title">{{item.title}}</div> <div class="quizz-hebdo-item-desc" v-html="item.description"></div> </div> </div>',
  });

  Vue.component("phdj", {
    props: ["dataRss", "dataName", "dataUrl", "styleNeeded"],
    mounted() {
      if(this.dataRss) {
        dataParse = JSON.parse(this.dataRss);
        this.goodData = dataParse.channel.item;
      }
    },
    data() {
      return { goodData: "" };
    },
    template:
      '<div> <div class="phdj-item" v-for="(item,index) in goodData" :key="index"> <div class="phdj-item-day">{{item.jour}}</div> <div class="phdj-item-title">{{item.title}}</div> <div class="phdj-item-desc" v-html="item.description"></div> </div> </div>',
  });

  Vue.component("indicateurs", {
    props: ["dataRss", "dataName", "dataUrl", "styleNeeded"],
    mounted() {
      if(this.dataRss) {
        dataParse = JSON.parse(this.dataRss);
        this.goodData = dataParse.channel.item;
      }
    },
    data() {
      return { goodData: "" };
    },
    template:
      '<div> <div class="indicateurs-item" v-for="(item,index) in goodData" :key="index"> <div class="indicateurs-item-day">{{item.jour}}</div> <div class="indicateurs-item-title">{{item.title}}</div> <div class="indicateurs-item-desc" v-html="item.description"></div> </div> </div>',
  });

  Vue.component("fiches", {
    props: ["dataRss", "dataName", "dataUrl", "styleNeeded"],
    mounted() {
      if(this.dataRss) {
        dataParse = JSON.parse(this.dataRss);
        this.goodData = dataParse.channel.item;
      }
    },
    data() {
      return { goodData: "" };
    },
    template:
      '<div> <div class="fiches-item" v-for="(item,index) in goodData" :key="index"> <div class="fiches-item-day">{{item.jour}}</div> <div class="fiches-item-title">{{item.title}}</div> <div class="fiches-item-desc" v-html="item.description"></div> </div> </div>',
  });

  Vue.component("agenda", {
    props: ["dataRss", "dataName", "dataUrl", "styleNeeded"],
    mounted() {
      if(this.dataRss) {
        dataParse = JSON.parse(this.dataRss);
        this.goodData = dataParse.channel.item;
      }
    },
    data() {
      return { goodData: "" };
    },
    template:
      '<div> <div class="agenda-item" v-for="(item,index) in goodData" :key="index"> <div class="agenda-item-day">{{item.jour}}</div> <div class="agenda-item-title">{{item.title}}</div> <div class="agenda-item-desc" v-html="item.description"></div> </div> </div>',
  });

  var apps = document.querySelectorAll(".weblex-rss-feed-app");

  for (i = 0; i < apps.length; ++i) {
    var vm = new Vue({ el: apps[i] });
  }