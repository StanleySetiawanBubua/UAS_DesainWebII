<template>
  <div>
    <v-container grid-list md>
      <v-subheader>
        All Books
      </v-subheader>
      <v-layout row wrap>
        <v-flex v-for="(book, index) in books" :key="index" xs6>
          <v-card :to="'/book/' + book.slug">
            <v-img :src="getImage(book.cover)" height="150px">
              <v-container fill-height fluid pa-2>
                <v-layout fill-height>
                  <v-flex xs12 align-end flexbox>
                    <span class="title white--text text-block">{{ book.title }}</span>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-img>
            <v-card-text>
              <div class="caption">
                <span class="author">{{ book.author }}</span>
                <span class="publisher">{{ book.publisher }}</span>
              </div>
              <div class="price">
                <span class="label">Price:</span>
                <span class="value">{{ book.price }}</span>
              </div>
              <div class="stock">
                <span class="label">Stock:</span>
                <span class="value">{{ book.stock }}</span>
              </div>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn icon>
                <v-icon>favorite</v-icon>
              </v-btn>
              <v-btn icon>
                <v-icon>bookmark</v-icon>
              </v-btn>
              <v-btn icon>
                <v-icon>share</v-icon>
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
    <v-pagination v-model="currentPage" @input="changePage" :length="lengthPage" :total-visible="4"></v-pagination>
  
  </div>
</template>

<style scoped>
.text-block {
  position: absolute;
  bottom: 5px;
  left: 5px;
  background-color: black;
  padding-left: 5px;
  padding-right: 5px;
  opacity: 0.7;
  width: 100%;
}

.caption {
  display: flex;
  justify-content: space-between;
  margin-bottom: 5px;
}

.author,
.publisher {
  font-size: 12px;
  color: gray;
}

.price,
.stock {
  font-size: 14px;
}

.label {
  font-weight: bold;
}

.value {
  margin-left: 5px;
}
</style>

<script>
export default {
  name: 'booksPage',
  data() {
    return {
      books: [],
      currentPage: 1,
      lengthPage: 0,
      perPage: 4,
    }
  },
  methods: {
    go() {
      let url = '/books?page=' + this.currentPage + '&per_page=' + this.perPage;
      this.axios.get(url)
        .then((response) => {
          let response_data = response.data;
          let books = response_data.data;
          this.lengthPage = response_data.meta.last_page;
          this.books = books;
        })
        .catch((error) => {
          if (error.response) {
            console.log(error.response);
          } else {
            console.log(error);
          }
        });
    },
    changePage(page) {
      this.currentPage = page;
      this.go();
    },
    getImage (image){
            if(image == null) return "/img/unavailable.jpg" 
            const imageDir = image.split('/')
            const imageName = imageDir[6];
            return process.env.VUE_APP_BACKEND_URL+'/images/'+imageName
        },
  },
  created() {
    this.go();
  },
}
</script>