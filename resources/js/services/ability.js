// const casl = require('@casl/ability');

// module.exports = function defineAbilitiesFor(user) {
//     return casl.AbilityBuilder.define(
//         { subjectName: item => item.type }, 
//         can => {
//             can(['read', 'create'], 'Post');
//             can(['update', 'delete'], 'Post', { user: user });
//         }
//     );
// };


import { Ability } from '@casl/ability';

export default new Ability()