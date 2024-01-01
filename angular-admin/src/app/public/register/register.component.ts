import {Component, OnInit} from '@angular/core';
import {FormsModule} from "@angular/forms";
import {HttpClient} from "@angular/common/http";
import {PublicModule} from "../public.module";
import {environment} from "../../../environments/environment";
import {Router} from "@angular/router";
import {AuthService} from "../../services/auth.service";


@Component({
  selector: 'app-register',
  standalone: true,
  imports: [
    FormsModule,
    PublicModule
  ],
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css', './../public.component.css']
})

export class RegisterComponent implements OnInit {

  // atributos acoplados a tag NgModel
  // public para serem acessados pelo form
  public name: string = '';
  public surname: string = '';
  public email: string = '';
  public password: string = '';
  public passwordConfirm: string = '';

  ngOnInit(): void {
  }

  constructor(private router: Router, private authSertvice: AuthService) {
    this.router = router;
    this.authSertvice = authSertvice;
  }

  public submit(): void {
    this.authSertvice.register( this.email, this.name, this.surname, this.password, this.passwordConfirm)
      .subscribe(
        (res) => {
          console.log(res)
          console.log(this.email)
          this.router.navigate(['/login']);
        }
      )
  }
}
