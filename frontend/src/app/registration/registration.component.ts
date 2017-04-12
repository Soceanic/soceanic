import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { Http } from '@angular/http';

@Component({
  selector: 'app-registration',
  templateUrl: './registration.component.html',
  styleUrls: ['./registration.component.css']
})
export class RegistrationComponent{

  errorMessage: string;
  registrationUrl: string = 'https://private-ad31e-soceanic.apiary-mock.com/users';
  registrationForm: FormGroup;
  response;

  register(){
    let formJson = JSON.stringify(this.registrationForm.getRawValue());
    this.http.post(this.registrationUrl, formJson)
      .subscribe(
        data => this.response = data,
        error => this.response = error
      );

  }

  constructor(private fb: FormBuilder, private http: Http) {
    this.registrationForm = fb.group({
      name: '',
      birthday: '',
      username: '',
      email: '',
      password: ''
    });

  }
}
