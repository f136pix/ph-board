import {Routes} from '@angular/router';
import {LoginComponent} from "./public/login/login.component";
import {RegisterComponent} from "./public/register/register.component";
import {SecureComponent} from "./secure/secure.component";
import {PublicComponent} from "./public/public.component";
import {ProfileComponent} from "./secure/profile/profile.component";
import {DashboardComponent} from "./secure/dashboard/dashboard.component";
import {UsersComponent} from "./secure/users/users.component";

export const routes: Routes = [
  {
    path: '',
    component: SecureComponent,
    children: [
      {path: '', redirectTo: '/dashboard', pathMatch: "full"},
      {path: 'dashboard', component: DashboardComponent},
      {path: 'profile', component: ProfileComponent},
      {path: 'users', component: UsersComponent},
    ]
  },
  {
    path: '',
    component: PublicComponent,
    children: [
      {path: 'login', component: LoginComponent},
      {path: 'register', component: RegisterComponent}]
  },
];
