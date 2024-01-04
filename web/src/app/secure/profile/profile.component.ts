import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, ReactiveFormsModule} from "@angular/forms";
import {AuthService} from "../../services/auth.service";
import {subscribe} from "node:diagnostics_channel";
import {HttpResponse} from "@angular/common/http";
import {Auth} from "../../classes/emitters/auth";
import {User} from "../../interfaces/user";

@Component({
  selector: 'app-profile',
  standalone: true,
  imports: [
    ReactiveFormsModule
  ],
  templateUrl: './profile.component.html',
  styleUrl: './profile.component.css'
})

export class ProfileComponent implements OnInit {
  public profileForm !: FormGroup;
  public passwordForm !: FormGroup;

  ngOnInit(): void {
    this.profileForm = this.formBuilder.group({
      name: '',
      surname: '',
      email: ''
    })

    this.passwordForm = this.formBuilder.group({
      password: "",
      password_confirm: ""
    })

    /*Auth.userEmitter.subscribe(
      (user: User): void => {
        console.log("Emitter recebido")
        // filling o form com os valores do user
        // this.profileForm.patchValue(user);
        this.profileForm.patchValue({
          name: user.name,
          surname: user.surname,
          email: user.email
        })
        this.emitterUsed = true;
      }
    )*/

      console.log('Forçado Fill')
      this.authService.currentUser().subscribe(
        (user: User) => this.profileForm.patchValue(user)
      )
  }

  constructor(private formBuilder: FormBuilder, private authService: AuthService) {
    this.formBuilder = formBuilder;
    this.authService = authService;
  }

  public profileSubmit(): void {
    this.authService.updateProfile(this.profileForm.getRawValue())
      .subscribe(
        (res: any) => Auth.userEmitter.emit(res.User)
      );
  }

  public passwordSubmit(): void {
    this.authService.updatePassword((this.passwordForm.getRawValue())
      .subscribe((res: object) => console.log(res)))
  }

  public fillForm(): void {

  }
}
