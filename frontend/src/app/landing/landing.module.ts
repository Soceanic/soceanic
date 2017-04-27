import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ReactiveFormsModule } from '@angular/forms';

import { SharedModule } from 'app/shared/shared.module';
import { Registration } from 'app/services/objects/registration';
import { RegistrationService } from 'app/services/registration.service';
import { RegistrationComponent } from 'app/landing/registration/registration.component';
import { LandingComponent } from 'app/landing/landing/landing.component';
import { ModalComponent } from 'app/shared/modal/modal.component';

import { LandingRoutingModule } from './landing-routing.module';

import { Login } from 'app/services/objects/login';
import { AuthService } from 'app/services/auth.service';
import { LoginComponent } from './login/login.component';


@NgModule({
  imports: [
    CommonModule,
    SharedModule,
    ReactiveFormsModule,
    LandingRoutingModule
  ],
  declarations: [RegistrationComponent, LandingComponent, LoginComponent],
  providers: [
    Registration,
    RegistrationService,
    Login,
    AuthService
  ],
  exports: [RegistrationComponent, LandingComponent, LoginComponent]
})

export class LandingModule { }
