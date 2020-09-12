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
            todo: true,
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
                                this.todo = true;
                               this.data.todoName = '';
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
            },
            updateTodoName(name) {
                this.data.todoUpdateName = name;
            },
            onUpdate(id) {
                this.data.todoUpdateName = this.$refs[id][0].value;
                var dataUpdate = {
                    name: this.data.todoUpdateName,
                };
                axios.post(object.root + 'jwptodo/v1/update/' + id, Qs.stringify(dataUpdate),
                        {
                            headers: {'X-WP-Nonce': object.nonce},
                        })
                        .then((response) => {
                            if (response.data == 0) {
                                alert('Data can not update');
                            }else{
                                alert('Updated');
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
            },
            onDelete(id) {
                if (confirm("Are You Sure?")) {
                    axios.delete(object.root + 'jwptodo/v1/delete/' + id,
                            {
                                headers: {'X-WP-Nonce': object.nonce},
                            })
                            .then((response) => {
                                if (response.data == 0) {
                                    alert('Data can not deleted');
                                } else {
                                    this.fetchTodo();
                                }
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                }
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
                            this.todos = response.data;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
            },
            showToDoList() {
                this.todo = true;
                this.fetchTodo();
            },
            makeItComplete() {
                var dataUpdate = {
                    ids: this.ids,
                };
                if (this.ids.length > 0) {
                    axios.post(object.root + 'jwptodo/v1/makeItComplete', Qs.stringify(dataUpdate),
                            {
                                headers: {'X-WP-Nonce': object.nonce},
                            })
                            .then((response) => {
                                this.ids = [];
                                this.fetchTodo();
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                } else {
                    alert('No todo selected');
                }
            },
            showCompletedList() {
                this.todo = false;
                this.fetchCompletedTodo();
                
            },
            deleteAllCompleted() {
                if (confirm("Are You Sure?")) {
                    axios.delete(object.root + 'jwptodo/v1/deleteAllCompleted',
                            {
                                headers: {'X-WP-Nonce': object.nonce},
                            })
                            .then((response) => {
                                if (response.data == 0) {
                                    alert('Data can not deleted');
                                } else {
                                    alert('All clear');
                                    this.fetchTodo();
                                    this.todo = true;
                                }
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                }
            }
        }
    });

})();