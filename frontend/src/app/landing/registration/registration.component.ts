import { Component, OnInit, OnDestroy } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';

import { RegistrationService } from 'app/services/registration.service';
import { Registration } from 'app/services/objects/registration';

import { Observable } from 'rxjs/Observable';

@Component({
  selector: 'app-registration',
  templateUrl: './registration.component.html',
  styleUrls: ['./registration.component.css']
})
export class RegistrationComponent implements OnInit, OnDestroy {

  private reg: Registration;
  form: FormGroup;
  submitted: boolean = false;
  err = null;

  constructor(private service: RegistrationService, private fb: FormBuilder, private router: Router) { }

  valid(){
    let isValid = true;
    this.form.status == 'VALID' ? isValid = true : isValid = false;
    return isValid;
  }

  ngOnInit() {
    this.reg = new Registration();
    this.buildForm();
  }

  ngOnDestroy() {
    this.reg = null;
  }

  buildForm(): void {
    this.form = this.fb.group({
      'first_name': [this.reg.first_name,
        [
          Validators.required
        ]
      ],
      'last_name': [this.reg.last_name,
        [
          Validators.required
        ]
      ],
      'username': [this.reg.username, [
        Validators.required,
        Validators.minLength(5),
        Validators.maxLength(20)
      ]
    ],
    'password': [this.reg.password,
      [
        Validators.required,
        Validators.minLength(8),

      ]
    ],
    'email': [this.reg.email,
      [
        Validators.required,
        Validators.email
      ]
    ],
    'birthday': [this.reg.birthday,
      [
        Validators.required
      ]
    ]
  });

  this.form.valueChanges.subscribe(data => this.onValueChanged(data));
  this.onValueChanged(); // (re)set validation messages now
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
  'first_name': '',
  'last_name': '',
  'username': '',
  'password': '',
  'email': '',
  'birthday': ''
};

validationMessages = {
  'first_name': {
    'required': 'First name is required'
  },
  'last_name': {
    'required': 'Last name is required'
  },
  'username': {
    'required': 'Username is required',
    'minlength': 'Username must be at least 5 characters',
    'maxLength': 'Username must be no more than 20 characters'
  },
  'password': {
    'required': 'Password is required',
    'minLength': 'Password must be at least 8 characters'
  },
  'email': {
    'required': 'Email is required',
    'email': 'Invalid email'
  },
  'birthday': {
    'required': 'Birthday is required'
  }
}


register(){
  this.submitted = true;
  this.reg = this.form.value;
  this.service.register(this.reg)
              .subscribe(
                user => {
                  console.log(user);
                  this.router.navigateByUrl('/login');
                },
                error => {
                  console.log(error);
                  this.err = error;
                }
              );
  }

}
