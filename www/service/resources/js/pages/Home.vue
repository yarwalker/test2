<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Форма обратной связи</div>
                    <div class="card-body">
                        <form @submit.prevent="sendEmail">
                            <div class="form-group">
                                <label for="name">Имя</label>
                                <input type="text"
                                       class="form-control"
                                       id="name"
                                       placeholder="Укажите ваше имя"
                                       required
                                       v-model.trim="name" >
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email"
                                       class="form-control"
                                       id="email"
                                       placeholder="Укажите ваш email"
                                       required
                                       v-model.trim="email" >
                            </div>
                            <div class="form-group">
                                <label for="message">Сообщение</label>
                                <textarea class="form-control"
                                          id="message"
                                          rows="5"
                                          required
                                          v-model="message"
                                ></textarea>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="personalAgree"
                                       v-model="pers_agree"
                                       true-value="1"
                                       false-value="0"
                                >
                                <label class="form-check-label" for="personalAgree">
                                    Согласие на обработку данных
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2 mt-3">Отправить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <modal v-if="showModal" @close="showModal = false">
            <div slot="body">{{ resultMessage }}</div>
            <h3 slot="header">Сообщение</h3>
        </modal>
    </div>
</template>

<script>
import Modal from '../pages/Modal.vue'

export default {
    name: 'Home',
    data() {
        return {
            name: '',
            email: '',
            message: '',
            pers_agree: 0,
            showModal: false,
            resultMessage: ''
        }
    },

    components: {
        modal: Modal,
    },

    methods: {
        sendEmail() {
            // try to store a new message
            axios.post('http://mailservice.loc/api/messages',
                {
                    name: this.name,
                    email: this.email,
                    message: this.message,
                    pers_agree: this.pers_agree
                }
            ).then((response) => {
                // console.log(response)
                this.resultMessage = response.data.result ? 'Ваше сообщение отправлено' : response.data.error
                this.showModal = true
                this.clearForm()
            }).catch((error) => {
                // console.log(error)
                this.resultMessage = error.data.error
                this.showModal = true
            })
        },
        clearForm() {
            this.name = ''
            this.email = ''
            this.message = ''
            this.pers_agree = 0
        }

    },

    created: function() {
        console.log('Form created')
    }
}
</script>
