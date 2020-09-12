(function () {
    Vue.component('todo-component', {
        props: ['todo'],
        template: '<li>{{todo}}</li>'
    })
    var vm = new Vue({
        el: document.querySelector('#jubayerID'),
        data: {
            data: {
                todoName: "",
                todoUpdateName: "",
            },
            todos: [],
            ids: [],
            count: 0,
            todo: true
        },
        mounted: function () {
            //console.log("Hello Vue!");
            this.fetchTodo();
            //this.fetchCompletedTodo();
        },
        methods: {
            onInsert() {
                var data = {
                    name: this.data.todoName,
                };
                axios.post(object.root + 'jwptodo/v1/store', Qs.stringify(data),
                        {
                            headers: {'X-WP-Nonce': object.nonce},
                        })
                        .then((response) => {
                            if (response.data == 0) {
                                alert('Data can not save');
                            } else {
                                this.fetchTodo();
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
            },
            updateId(id) {
                var exists = this.ids.some(function (field) {
                    return field === id
                });

                if (!exists) {
                    this.ids.push(id);
                } else {
                    const index = this.ids.indexOf(id);
                    if (index > -1) {
                        this.ids.splice(index, 1);
                    }
                }

                console.log(this.ids);
            },
            updateTodoName(name) {
                this.data.todoUpdateName = name;
            },
            onUpdate(id) {
                alert(id);
            },
            onDelete(id) {
                alert('delete');
            },
            fetchTodo() {
                axios.get(object.root + 'jwptodo/v1/all', {
                    headers: {'X-WP-Nonce': object.nonce}
                })
                        .then((response) => {
                            this.todos = response.data;
                            this.count = response.data.length;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
            },
            fetchCompletedTodo() {
                axios.get(object.root + 'jwptodo/v1/allComplete', {
                    headers: {'X-WP-Nonce': object.nonce}
                })
                        .then((response) => {

                        })
                        .catch((error) => {
                            console.log(error);
                        });
            }
        }
    });

})();