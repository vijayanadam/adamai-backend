import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../views/Dashboard.vue";
import Tables from "../views/Tables.vue";
import Billing from "../views/Billing.vue";
import RTL from "../views/Rtl.vue";
import Notifications from "../views/Notifications.vue";
import Profile from "../views/Profile.vue";
import SignIn from "../views/SignIn.vue";
import SignUp from "../views/SignUp.vue";
import Login from "../views/examples-api/Login.vue";
import Signup from "../views/examples-api/Signup.vue";
import PasswordForgot from "../views/examples-api/PasswordForgot.vue";
import PasswordReset from "../views/examples-api/PasswordReset.vue";
import UserProfile from "../views/examples-api/profile/UserProfile.vue";
import Users from "../views/examples-api/users/UsersList.vue";
import Users1 from "../views/Users1.vue";
import AddUser from "../views/AddUser.vue";
import Vbots from "../views/Vbots.vue";
import Calls from "../views/Calls.vue";
import AddVbot from "../views/AddVbot.vue";
import CompanyProfile from "../views/CompanyProfile.vue";
import ChangePassword from "../views/ChangePassword.vue";
import EditUser from "../views/EditUser.vue";
import EditVbot from "../views/EditVbot.vue";
import Phonebots from "../views/Phonebots.vue";
import EditPhonebot from "../views/EditPhonebot.vue";
import MyProfile from "../views/MyProfile.vue";
import ViewCallLogs from "../views/ViewCallLogs.vue";
import store from "@/store";
import NewCalls from "../views/NewCalls.vue";
const routes = [
  
  {
    path: "/",
    name: "/",
    redirect: "/login",
  },
  {
    path: "/users1",
    name: "users1",
    component: Users1,
    meta: {
      authRequired: true,
     
    }
  },
  {
    path: "/addusers",
    name: "addusers",
    component: AddUser,
    meta: {
      authRequired: true,
     
    }
  },
  {
    path: '/edituser/:id',
    name: 'EditUser',
    component: EditUser,
    meta: {
      authRequired: true,
     
    }
   
  },
  {
    path: "/vbots",
    name: "vbots",
    component: Vbots,
    meta: {
      authRequired: true,
     
    }
  },
  {
    path: "/phonebots",
    name: "phonebots",
    component: Phonebots,
    meta: {
      authRequired: true,
     
    }
  },
  {
    path: '/editvbot/:id',
    name: 'EditVbot',
    component: EditVbot,
    meta: {
      authRequired: true,
     
    }
   
  },
  {
    path: '/viewcalllogs',
    name: 'ViewCallLogs',
    component: ViewCallLogs,
    meta: {
      authRequired: true,
     
    }
   
  },
  {
    path: '/editphonebot/:id',
    name: 'EditPhonebot',
    component: EditPhonebot,
    meta: {
      authRequired: true,
     
    }
   
  },
  
  {
    path: "/calls",
    name: "calls",
    component: Calls,
    meta: {
      authRequired: true,
     
    }
  },
  {
    path: "/addvbot",
    name: "addvbot",
    component: AddVbot,
    meta: {
      authRequired: true,
     
    }
  },
  {
    path: "/companyprofile",
    name: "companyprofile",
    component: CompanyProfile,
    meta: {
      authRequired: true,
     
    }
  },
  {
    path: "/changepassword",
    name: "changepassword",
    component: ChangePassword,
    meta: {
      authRequired: true,
     
    }
  },
  {
    path: "/myprofile",
    name: "myprofile",
    component: MyProfile,
    meta: {
      authRequired: true,
     
    }
  },
  {
    path: "/dashboard",
    name: "Dashboard",
    component: Dashboard,
    meta: {
      authRequired: true,
     
    }
  },
  {
    path: '/newcalls',
    name: 'NewCalls',
    component: NewCalls,
    meta: {
      authRequired: true,
     
    }
   
  },
  {
    path: "/tables",
    name: "Tables",
    component: Tables,
  },
  {
    path: "/billing",
    name: "Billing",
    component: Billing,
  },
  {
    path: "/rtl-page",
    name: "RTL",
    component: RTL,
  },
  {
    path: "/notifications",
    name: "Notifications",
    component: Notifications,
  },
  {
    path: "/profile",
    name: "Profile",
    component: Profile,
  },
  {
    path: "/sign-in",
    name: "SignIn",
    component: SignIn,
  },
  {
    path: "/sign-up",
    name: "SignUp",
    component: SignUp,
  },
  {
    path: "/login",
    name: "Login",
    component: Login
  },
  {
    path: "/signup",
    name: "Signup",
    component: Signup
  },
  {
    path: "/password-forgot",
    name: "Password Forgot",
    component: PasswordForgot
  },
  {
    path: "/password-reset",
    name: "Password Reset",
    component: PasswordReset
  },
  {
    path: "/user-profile",
    name: "User Profile",
    component: UserProfile
  },
  {
    path: '/users',
    name: "Users",
    component: Users
  }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
  linkActiveClass: "active",
});

//eslint-disable-next-line no-unused-vars
router.beforeEach(async (to, from, next) => {

  const { authRequired, permission } = to.meta;

  const isLoggedIn = store.getters['auth/isLoggedIn'];

  if (authRequired) {

    //if trying to access a page that requires authentication but not logged in, redirect to login page
    if (!isLoggedIn) {
      return next({ name: 'Login', query: { returnUrl: to.path } });
    }


    //if trying to access a page that requires permission, redirect to dashboard
    if (permission) {
      try {
        await store.dispatch('profile/getProfile')
        const userPermission = (store.getters['profile/getUserProfile']).roles[0].name;
        if (!permission.includes(userPermission)) {
          return next({ name: 'Default', query: { returnUrl: to.path } });
        }
      }catch(error){
        try{
          store.dispatch('auth/logout');
        }finally{
          // eslint-disable-next-line no-unsafe-finally
          return next({name: "Login"});
        }
      }
    }

  }
  next();
})

export default router;

