import { Component, OnInit, OnDestroy } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';

import { AuthService } from 'app/services/auth.service';
import { Login } from 'app/services/objects/login';

import { Observable } from 'rxjs/Observable';

import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit, OnDestroy {

  private _login: Login;
  form: FormGroup;
  submitted: boolean = false;
  err = null;

  constructor(private service: AuthService, private fb: FormBuilder, private router: Router) { }

  ngOnInit() {
    this._login = new Login();
    this.buildForm();
  }

  ngOnDestroy() {
    this._login = null;
  }

  buildForm(): void {
    this.form = this.fb.group({
      'username': [this._login.username,
        [
          Validators.required,
          Validators.minLength(5),
          Validators.maxLength(20)
        ]
      ],
      'password': [this._login.password,
        [
          Validators.required,
          Validators.minLength(8)
        ]
    ]
    });

    this.form.valueChanges.subscribe(data => this.onValueChanged(data));
    this.onValueChanged();
  }

  onValueChanged(data?: any) {
    if (!this.form) { return; }
    const form = this.form;
    for (const field in this.formErrors) {
      // clear previous error message (if any)
      this.formErrors[field] = '';
      const control = form.get(field);
      if (control && control.dirty && !control.valid) {
        const messages = this.validationMessages[field];
        for (const key in control.errors) {
          this.formErrors[field] += messages[key] + ' ';
        }
      }
    }
  }

  formErrors = {
    'username': '',
    'password': ''
  }

  validationMessages = {
    'username': {
      'required': 'Username is required',
      'minlength': 'Username must be at least 5 characters',
      'maxLength': 'Username must be no more than 20 characters'
    },
    'password': {
      'required': 'Password is required',
      'minLength': 'Password must be at least 8 characters'
    }
  }

  login() {
    this.submitted = true;
    this._login = this.form.value;
    this.service.login(this._login)
                .subscribe(
                  user => console.log(user),
                  error => this.err = error
                );
    if(!this.err){
      this.router.navigateByUrl('/feed');
    }
  }

}
