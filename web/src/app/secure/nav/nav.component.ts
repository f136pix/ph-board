import {Component, Input, OnInit} from '@angular/core';
import {AuthService} from "../../services/auth.service";
import {Router, RouterLink} from "@angular/router";
import {User} from "../../interfaces/user";
import {Auth} from "../../classes/emitters/auth";

@Component({
  selector: 'app-nav',
  standalone: true,
  imports: [
    RouterLink
  ],
  templateUrl: './nav.component.html',
  styleUrl: './nav.component.css'
})
export class NavComponent implements OnInit {
  // valor recebido como parametro em cada <app-nav/>
  //@Input('User') public user!: User;

  public user!: User;

  constructor(private authService: AuthService, private router: Router) {
    this.authService = authService;
    this.router = router;
  }

  // recebendo o user atual atraves do emitter
  // em secure.component.ts
  ngOnInit(): void {
    Auth.userEmitter.subscribe(
      (user: User) => {
        console.log("Nav component Emitter rebeceu")
        this.user = user
      }
    )
  }

  public logout(): void {
    this.authService.logout()
      .subscribe((): void => {
          console.log('User deslogado!')
        }
      );
  }
}
