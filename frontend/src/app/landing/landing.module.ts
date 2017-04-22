import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AppModule } from 'app/app.module';

import { Registration } from 'app/services/objects/registration';
import { RegistrationService } from 'app/services/registration.service';
import { RegistrationModalComponent } from 'app/landing/registration-modal/registration-modal.component';
import { LandingComponent } from 'app/landing/landing/landing.component';
import { ModalComponent } from 'app/shared/modal/modal.component';

@NgModule({
  imports: [
    CommonModule,
    AppModule,
    ModalComponent
  ],
  declarations: [RegistrationModalComponent, LandingComponent],
  providers: [
    Registration,
    RegistrationService
  ]
})

export class LandingModule { }
