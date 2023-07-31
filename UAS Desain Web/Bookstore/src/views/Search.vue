<template>
    <v-card>
      <v-toolbar dark color="primary">
        <v-btn icon dark @click.native="close">
          <v-icon>close</v-icon>
        </v-btn>
        <v-text-field v-model="keyword" @input="doSearch" hide-details append-icon="mic" text label="Search" prepend-inner-icon="search" solo-inverted ref="txtSearch"></v-text-field>
      </v-toolbar>
      <v-divider></v-divider>
      <v-subheader v-if="keyword.length>0">
        Result search "{{ keyword }}"
      </v-subheader>
      <v-alert :value="items.length===0 && keyword.length>0" color="warning">
        Sorry, but no results were found.
      </v-alert>
  
      <v-list two-line v-if="items.length>0">
        <v-list-item v-for="item in items" :key="item.id" @click="close" :to="'/book/'+ item.slug">
          <v-list-item-avatar>
            <img :src="getImage(item.cover)" alt="Book Cover">
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title v-html="item.title"></v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-card>
  </template>
  
  <script>
  import { mapActions } from 'vuex';
  
  export default {
    name: 'SearchPage',
    data() {
      return {
        keyword: '',
        items: [],
      };
    },
    methods: {
      ...mapActions({
        setStatusDialog: 'dialog/setStatus',
      }),
      close() {
        this.setStatusDialog(false);
      },
      doSearch() {
        let keyword = this.keyword;
        if (keyword.length > 0) {
          this.axios
            .get('/books/search/' + keyword)
            .then((response) => {
              let books = response.data.data;
              this.items = books;
            })
            .catch((error) => {
              console.log(error);
            });
        }
      },
      getImage (image){
            if(image == null) return "/img/unavailable.jpg" 
            const imageDir = image.split('/')
            const imageName = imageDir[6];
            return process.env.VUE_APP_BACKEND_URL+'/images/'+imageName
        },
    },
    mounted() {
      if (this.$refs.txtSearch !== undefined) {
        this.$nextTick(() => this.$refs.txtSearch.focus());
      }
    },
  };
  </script>
  