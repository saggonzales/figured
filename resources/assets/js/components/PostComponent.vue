<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <!-- <div class="panel-heading">Post Component</div> -->

                    <form @submit.prevent="addPost()" class="mb-3">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Title" v-model="post.title" />
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" rows="10" placeholder="Text" v-model="post.text"></textarea>
                        </div>
                        <button type="submit" class="btn btn-light btn-block">Save</button>

                    </form>




                    <div class="panel-body">
                        
                        <div card card-body v-for="post in posts" v-bind:key="post.id">
                            <h3>{{ post.title}}</h3>                            
                            <p> {{ post.text }} </p>
                            <hr />
                            <button class="btn btn-warning" @click="editPost(post)">Edit</button>
                            <button class="btn btn-danger" @click="deletePost(post.id)">Delete</button>
                            <hr />
                        </div>

                    </div>


                    <div class="panel-footer">

                        <nav aria-label="Page navigation example">
                          <ul class="pagination">
                            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                                <a @click="fetchPosts(pagination.prev_page_url)" class="page-link" href="#">Previous</a>
                            </li>


                            <li  class="page-item disabled">
                                <a class="page-link text-dark" href="#">Page {{pagination.current_page}} of {{pagination.last_page}} </a>
                            </li>


                            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                                <a @click="fetchPosts(pagination.next_page_url)" class="page-link" href="#">Next</a>
                            </li>
                          </ul>
                        </nav>

                    </div>



                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },        
        data() {
            return {
                posts: [],
                post: {
                    id: '',
                    title: '',
                    text: ''
                },
                post_id: '',
                pagination: {},
                edit: false
            }
        },
        created(){
            this.fetchPosts();
        },
        methods: {
            fetchPosts(page_url){                
                fetch('api/posts')
                let vm = this;
                page_url = page_url || 'api/posts'
                fetch(page_url)
                    .then( res => res.json())
                    .then( res => {
                        // console.log(res.data);
                        this.posts = res.data;
                        console.log(this.posts);
                        vm.makePagination(res.meta, res.links);
                    })
                    .catch(err => console.log(err));
            },
            makePagination(meta, links){
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                }
                this. pagination = pagination;
            },
            deletePost(id){
                console.log(id);
                if (confirm('Are you sure')) {
                   fetch(`api/post/${id}`,{
                        method: 'delete'
                   })
                   .then(res => res.json())
                   .then(data => {
                        this.fetchPosts();
                   })
                   .catch(err => console.log(err)); 
                }
            },
            addPost(){
                if (this.edit === false) {
                    // Add
                    fetch('api/post', {
                        method: 'post',
                        body: JSON.stringify(this.post),
                        headers: {
                            'content-type':'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.post.title = '';
                        this.post.text = '';                        
                        this.fetchPosts();
                    })
                    .catch(err => console.log(err))
                } else {
                    // Update
                    fetch('api/post', {
                        method: 'put',
                        body: JSON.stringify(this.post),
                        headers: {
                            'content-type':'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.post.title = '';
                        this.post.text = '';                        
                        this.fetchPosts();
                    })
                    .catch(err => console.log(err))

                }
            },
            editPost(post){
                console.log(post);
                this.edit = true;
                this.post.id = post.id;
                this.post.post_id = post.id;
                this.post.title = post.title;
                this.post.text = post.text;
            }

        }
    };
</script>
