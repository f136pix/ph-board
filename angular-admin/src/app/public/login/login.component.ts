import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, FormsModule, ReactiveFormsModule} from "@angular/forms";
import {HttpClient, HttpClientModule, HttpStatusCode} from "@angular/common/http";
import {PublicModule} from "../public.module";
import {Router} from "@angular/router";
import {AuthService} from "../../services/auth.service";
import {Auth} from "../../classes/emitters/auth";
import {User} from "../../interfaces/user";

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [
    FormsModule,
    ReactiveFormsModule,
    PublicModule
  ],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css', './../public.component.css']
})

export class LoginComponent implements OnInit {

  // @ts-ignore
  public form: FormGroup;
  private email = '';
  private password = ''
  private authenticatedUser !: User;

  ngOnInit(): void {
    this.form = this.formBuilder.group({
      email: '',
      password: ''
    });
  }

  constructor(private formBuilder: FormBuilder, private router: Router, private authService: AuthService) {
    this.formBuilder = formBuilder;
    this.router = router;
    this.authService = authService
  }


  public submit(): void {

    this.email = this.form.getRawValue().email;
    this.password = this.form.getRawValue().password;

      this.authService.login(this.email, this.password)
      .subscribe(
        (res) => {
          console.log(res)
          this.router.navigate(['/']);
        },
        (err) => {
          console.error(err);
        }
      )
  }

}
