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
  registrationUrl: string = 'http://vapeboyz.xyz/user';
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
      first_name: '',
      last_name: '',
      birthday: '',
      username: '',
      email: '',
      password: ''
    });

  }
}
