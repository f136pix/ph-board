import {Component, OnInit} from '@angular/core';
import {UserService} from "../../services/user.service";
import {User} from "../../interfaces/user";
import {CommonModule} from "@angular/common";

@Component({
  selector: 'app-users',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './users.component.html',
  styleUrl: './users.component.css'
})
export class UsersComponent implements OnInit {
  users: User[] = [];
  // html table pagination
  page: number = 1;
  lastPage!: number;
  firstPage: number = 1;

  constructor(private userService: UserService) {
    this.userService = userService
  }

  ngOnInit(): void {
    this.load();
  }

  public load(): void {
    this.userService.all(this.page).subscribe(
      (res: any) => {
        this.users = res.data;
        this.lastPage = res.last_page;
      }
    )
  }

  public previousPage(): void {
    if (this.page !== this.firstPage) {
      this.page--;
      this.load()
    }
  }

  public nextPage(): void {
    if (this.page == this.lastPage) {
      return
    }
    this.page++;
    this.load()
  }
}
