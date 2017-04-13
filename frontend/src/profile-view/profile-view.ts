import { Component } from '@angular/core';
import { UserRepository } from '../api/user-repository';

@Component({
  moduleId: module.id,
  selector: 'profile-view',
  templateUrl: 'profile-view.component.html',
  styleUrls: [ 'profile-view.component.css' ],
})

export class AccountListComponent { 
    profilePicture: string;
    userName: string;
    userbio: string; 

    constructor() {
      this.picture = userRepository.getProfilePicture();

      this.user = userRepository.getUser();

      this.bio = userRepository.getUserBio();
    }
}