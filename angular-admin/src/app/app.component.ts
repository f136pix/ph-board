import {Component, isDevMode} from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterOutlet } from '@angular/router';
import {NavComponent} from "./secure/nav/nav.component";
import {MenuComponent} from "./secure/menu/menu.component";
import {MainComponent} from "./secure/main/main.component";
import {SecureComponent} from "./secure/secure.component";
import {environment} from "../environments/environment";

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, RouterOutlet, NavComponent, MenuComponent, MainComponent, SecureComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = 'angular-admin';

  // verificando se estamos em dev ou prod
  ngOnInit() {
    if (isDevMode()) {
      console.log('Development!');
    } else {
      console.log('Production!');
    }
  }
}

