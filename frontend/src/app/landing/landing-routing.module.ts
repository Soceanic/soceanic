import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { LandingComponent } from './landing/landing.component';
import { RegistrationComponent } from './registration/registration.component';

const landingRoutes: Routes = [
  { path: 'home', component: LandingComponent },
  { path: 'register', component: RegistrationComponent}
];

@NgModule({
  imports: [RouterModule.forChild(landingRoutes)],
  exports: [RouterModule]
})

export class LandingRoutingModule { }
