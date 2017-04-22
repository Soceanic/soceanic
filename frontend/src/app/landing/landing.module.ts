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

@NgModule({
  imports: [
    CommonModule,
    SharedModule,
    ReactiveFormsModule,
    LandingRoutingModule
  ],
  declarations: [RegistrationComponent, LandingComponent],
  providers: [
    Registration,
    RegistrationService
  ],
  exports: [RegistrationComponent, LandingComponent]
})

export class LandingModule { }
