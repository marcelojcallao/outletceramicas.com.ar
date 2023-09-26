let user = document.head.querySelector('meta[name="user"]');
let company = document.head.querySelector('meta[name="company"]');

export default {

    computed: {
        
        User(){
            return JSON.parse(user.content);
        },

        IsAuthenticated(){
            return !! user.content;
        },

        Guest(){
            return ! this.IsAuthenticated;
        },
        
        Company(){
            return JSON.parse(company.content);
        }
    },

    methods: {
        
        RedirectIfGuest(){
            if (this.Guest) return window.location.href = '/login'
        },

        async logOutUser(){
            try {
                const response = await axios.post('/api/logout', {user_id : this.User.id});
                
                if (response.status == 200) {
                    const user_meta = document.getElementById('user_meta');

                    user_meta.content = '';
                    console.log('user_meta');
                    console.log(user_meta);
                    console.log('user_meta');
                    console.log('user');
                    console.log(user);
                    console.log('user');


                   window.location.replace("/login");
                }
                return response;
    
            } catch (error) {
                throw error;
            }
        },
    },
}