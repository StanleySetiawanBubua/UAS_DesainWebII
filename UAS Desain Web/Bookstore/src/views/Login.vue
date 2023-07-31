<template>
    <v-card>
        <v-toolbar dark color="primary">
            <v-btn icon dark @click="close">
                <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title>Login and Start Shopping!</v-toolbar-title>
        </v-toolbar>
        <v-divider></v-divider>
        <v-container fluid>
            <v-form ref="form" v-model="valid" lazy-validation>
                <v-text-field v-model="email" :rules="emailRules" label="E-mail" required append-icon="email"></v-text-field>
                <v-text-field v-model="password" :append-icon="showPassword ? 'visibility' : 'visibility_off'" :rules
                ="passwordRules" :type="showPassword ? 'text' : 'password'" label="Password" hint="At least 6
                charaacters" counter @click:append="showPassword = !showPassword"></v-text-field>
                <div class="text-xs-center">
                    <v-btn color="accent lighten-1" :disabled="!valid" @click="submit">
                        Login
                        <v-icon right dark>lock_open</v-icon>
                    </v-btn>
                </div>
            </v-form>
        </v-container>
    </v-card>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    export default {
        name: 'LoginPage',
        data() {
            return {
                valid: true,
                email: 'henny@example.org',
                emailRules: [
                v => !!v || 'E-mail is required',
                v => /([a-zA-Z0-9_]{1,})(@)([a-zA-Z0-9_]{2,}).([a-zA-Z0-9_]{2,})+/.test(v) || 'E-mail must be valid'
                ],
                showPassword: false,
                password: '',
                passwordRules: [
                    v => !!v || 'password required.',
                    v => (v && v.length >= 6) || 'Min 6 Characters',
                ],
            }
        },
        computed: {
      ...mapGetters({
        user: 'auth/user',
        prevUrl: 'prevUrl',
      }),
    },
    methods: {
      ...mapActions({
        setAlert: 'alert/set',
        setStatusDialog: 'dialog/setStatus',
        setAuth: 'auth/set',
      }),
      close() {
        this.setStatusDialog(false);
      },
      submit() {
        if (this.$refs.form.validate()) {
          let formData = {
            email: this.email,
            password: this.password,
          };
          this.axios
            .post('/login', formData)
            .then((response) => {
              let data_user = response.data.data;
              this.setAuth(data_user);
              if (this.user.id > 0) {
                this.setAlert({
                  status: true,
                  text: 'Login Success',
                  type: 'success',
                });
                if(this.prevUrl.length>0) this.$router.push(this.prevUrl)
                  this.close()
              } else {
                this.setAlert({
                  status: true,
                  text: 'Login Error',
                  type: 'error',
                });
              }
            })
            .catch((error) => {
              let response = error.response;
              this.setAlert({
                status: true,
                text: response.data.message,
                type: 'error',
              });
            });
        }
      },
    },
  };
</script>