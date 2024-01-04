import {Component, Input, OnInit} from '@angular/core';
import {MenuComponent} from "./menu/menu.component";
import {MainComponent} from "./main/main.component";
import {NavComponent} from "./nav/nav.component";
import {AuthService} from "../services/auth.service";
import {Router} from "@angular/router";
import {User} from "../interfaces/user";
import {Auth} from "../classes/emitters/auth";
import {CommonModule} from "@angular/common";

@Component({
  selector: 'app-secure',
  standalone: true,
  imports: [
    MenuComponent,
    MainComponent,
    NavComponent,
    CommonModule
  ],
  templateUrl: './secure.component.html',
  styleUrl: './secure.component.css'
})
export class SecureComponent implements OnInit {
  user!: User;

  constructor(private authService: AuthService, private router: Router) {
    this.authService = authService;
    this.router = router
  }

  ngOnInit(): void {
    this.authService.currentUser().subscribe(
      (res :User): void => {
        console.log('Secure Component emitter :', res)
        this.user = res
        Auth.userEmitter.emit(this.user);
      },
      (err) => {
        console.log(`User não encontrado : ${err}`);
        this.router.navigate(['/login']);
      }
    );

    Auth.userEmitter.subscribe(
      (user: User) => this.user = user
    );
  }

}
