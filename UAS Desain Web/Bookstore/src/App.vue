<template>
  <v-app>
    <c-header />

    <c-side-bar />

    <v-content>
      <v-slide-y-transition mode="out-in">
        <router-view></router-view>
      </v-slide-y-transition>
    </v-content>

    <c-footer />

    <c-alert />

    <!--v-dialog v-model="statusDialog" fullscreen hide-overlay transition="dialogbottom-transition">
      <search />
    </v-dialog> -->

    <keep-alive>
      <v-dialog v-model="statusDialog" fullscreen hide-overlay transition="dialogbottom-transition">
      <component :is="currentComponent"></component>
    </v-dialog> 
    </keep-alive>

  </v-app>
</template>

<script>
  export default {
    name: 'App'
  }
</script>

<style type="text/css">
  .v-toolbar {
    flex: 0 !important
  }

  .v-application .py-3 {
    text-align: center !important
  }

  .v-card_text {
    text-align: center !important
  }
</style>

<script>
import { mapGetters, mapActions } from 'vuex';
import CHeader from '@/components/CHeader.vue';
import CFooter from '@/components/CFooter.vue';
import CSideBar from '@/components/CSideBar.vue';
import CAlert from '@/components/CAlert.vue';
import Search from '@/views/Search.vue';
import Login from '@/views/Login.vue';
import Register from '@/views/Register.vue'
import Cart from '@/views/Cart.vue'

export default {
  name: 'App',
  components: {
    CHeader,
    CFooter,
    CSideBar,
    CAlert,
    Search,
    Login,
    Register,
    Cart,
  },
  methods: {
    ...mapActions({
      setStatusDialog: 'dialog/setStatus',
    }),
    setDialogStatus(value) {
      this.setStatusDialog(value);
    },
  },
  computed: {
    ...mapGetters({
      dialogStatus: 'dialog/status',
      currentComponent: 'dialog/component',
    }),
    statusDialog: {
      get() {
        return this.dialogStatus;
      },
      set(value) {
        this.setDialogStatus(value);
      },
    },
  },
};
</script>

