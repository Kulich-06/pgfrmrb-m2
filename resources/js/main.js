const app = new Vue({ 
    el:'#app', 
    data:{  
        page: "index",
        regResponse: null, 
        message:"", 
        registerForm:{  
           name:"", 
           login:"", 
           email:"", 
           phone:"", 
           password:"" 
        }, 
        server: "http://pgfrmrb-m2.wsr.ru/api" 
    }, 
methods: { 
    register(){ 
        this.page = "registration";
       fetch('http://pgfrmrb-m2.wsr.ru/api/register', { 
           method:'POST', 
           body:JSON.stringify({ 
               name :this.registerForm.name, 
               login:this.registerForm.login, 
               email:this.registerForm.email, 
               phone:this.registerForm.phone, 
               password:this.registerForm.password 
           }), 
           headers:{"Content-Type":"application/json;charset=utf-8"} 
       }) 
       .then(response =>response.json().then( 
           data => { 
               this.regResponse.status=response.status; 
    this.regResponse.body=response.data; 
           } 
       )) 
       .catch(error => { 
            console.error(error); 
               this.regResponse.status=response.status; 
    this.regResponse.body=response.error; 

})}}
});