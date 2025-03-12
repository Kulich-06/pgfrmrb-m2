const app = new Vue({
    el: "#app",
    data: {
        page: "index",
        regResponse: null,
        logResponse: null,
        addClotchResponse: null,
        addCategoryResponse: null,
        addColorResponse: null,
        userId: null,
        userToken: null,
        res: {},
        categories: [],
        seasons: [],
        colors: [],
        clotches: {},
        message: "",
        id: null,
        addColorForm: {
            name: "",
        },
        addCategoryForm: {
            name: "",
        },
        addClotchForm: {
            name: "",
            size: "",
            season_id: null,
            color_id: null,
            category_id: null,
        },
        registerForm: {
            name: "",
            login: "",
            email: "",
            phone: "",
            password: "",
        },
        loginForm: {
            login: null,
            password: null,
        },
    },
    mounted() {
        this.getColor();
        this.getCategory();
        this.getSeason();
        this.getClotch();
    },
    methods: {
        getColor() {
            fetch("http://localhost/pgfrmrb-m2/api/colors")
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    this.colors = data;
                });
        },
        getCategory() {
            fetch("http://localhost/pgfrmrb-m2/api/categories")
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    this.categories = data;
                });
        },
        getSeason() {
            fetch("http://localhost/pgfrmrb-m2/api/seasons")
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    this.seasons = data;
                });
        },
        getClotch() {
            fetch("http://localhost/pgfrmrb-m2/api/clotches")
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    this.clotches = data;
                });
        },
        register() {
            this.page = "registration";
            fetch("http://localhost/pgfrmrb-m2/api/register", {
                method: "POST",
                body: JSON.stringify({
                    name: this.registerForm.name,
                    login: this.registerForm.login,
                    email: this.registerForm.email,
                    phone: this.registerForm.phone,
                    password: this.registerForm.password,
                }),
                headers: { "Content-Type": "application/json;charset=utf-8" },
            })
                .then((response) =>
                    response.json().then((data) => {
                        console.log(data);
                        this.message = data.message;
                        this.regResponse = {
                            status: response.status,
                            body: data,
                        };
                    })
                )
                .catch((error) => {
                    console.error(error);
                    this.regResponse = { status: response.status, body: error };
                });
        },
        login() {
            fetch(this.host + "/login", {
                method: "POST",
                body: JSON.stringify({
                    login: this.loginForm.login,
                    password: this.loginForm.password,
                }),
                headers: {
                    "Content-Type": "application/json;charset=utf-8",
                    Accept: "application/json",
                },
            })
                .then((response) =>
                    response.json().then((data) => {
                        this.logResponse = {
                            status: response.status,
                            body: data,
                        };
                        if (data.id && data.token) {
                            window.localStorage.setItem("token", data.token);
                            window.localStorage.setItem("userid", data.id);
                            this.userToken =
                                window.localStorage.getItem("token");
                            this.userId = window.localStorage.getItem("userid");
                        }
                    })
                )
                .catch((error) => {
                    console.error(error);
                });
        },
        getClotch() {
            fetch("http://localhost/pgfrmrb-m2/api/clotches")
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    this.clotches = data;
                });
        },
        addClotch() {
            fetch("http://localhost/pgfrmrb-m2/api//clotches", {
                method: "POST",
                body: JSON.stringify({
                    name: this.addClotchForm.name,
                    size: this.addClotchForm.size,
                    category_id: this.addClotchForm.category_id,
                    season_id: this.addClotchForm.season_id,
                    color_id: this.addClotchForm.color_id,
                }),
                headers: {
                    "Content-Type": "application/json;charset=utf-8",
                    Accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    this.message = data.message;
                    this.id = data.id;
                    this.addClotchResponse = {
                        status: response.status,
                        body: data,
                    };
                })
                .catch((error) => console.log("error", error));
        },
        addCategory() {
            fetch("http://localhost/pgfrmrb-m2/api/categories", {
                method: "POST",
                body: JSON.stringify({
                    name: this.addCategoryForm.name,
                }),
                headers: {
                    "Content-Type": "application/json;charset=utf-8",
                    Accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    this.message = data.message;
                    this.id = data.id;
                    this.addCategoryResponse = {
                        status: response.status,
                        body: data,
                    };
                })
                .catch((error) => console.log("error", error));
        },
        addColor() {
            fetch("http://localhost/pgfrmrb-m2/api/colors", {
                method: "POST",
                body: JSON.stringify({
                    name: this.addColorForm.name,
                }),
                headers: {
                    "Content-Type": "application/json;charset=utf-8",
                    Accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    this.message = data.message;
                    this.id = data.id;
                    this.addColorResponse = {
                        status: response.status,
                        body: data,
                    };
                })
                .catch((error) => console.log("error", error));
        },
    },
});
