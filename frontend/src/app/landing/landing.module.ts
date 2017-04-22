import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { Registration } from '../services/objects/registration';
import { RegistrationService } from '../services/registration.service';
import { RegistrationModalComponent } from './registration-modal/registration-modal.component';

@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [RegistrationModalComponent],
  providers: [
    Registration,
    RegistrationService
  ]
})
export class LandingModule { }
